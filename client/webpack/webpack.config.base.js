const path = require('path');
const extractTextPlugin = require("extract-text-webpack-plugin");
const vueLoaderPlugin = require('vue-loader/lib/plugin');
const htmlPlugin = require('html-webpack-plugin');

const fs = require('fs');

//webpack
// const webpack = require('webpack');

//多线程打包, 使用 happypack@next
const happyPack = require('happypack');
const os = require('os');
const happyThreadPool = happyPack.ThreadPool({
    size: os.cpus().length
});

const srcPath = path.resolve(__dirname, '../src');
const outputPath = path.resolve(__dirname, '../dist');
const uploadsPath = path.join(outputPath, 'uploads');

const copyFile = require('copy-webpack-plugin');

//如果dist文件不存在就创建
try {
    fs.statSync(outputPath);
} catch (error) {
    fs.mkdirSync(outputPath);    
}

//创建uplaod文件
try {
    fs.statSync(uploadsPath);
} catch (error) {
    fs.mkdirSync(uploadsPath);
}

//移除dist生成的path
function rmGenFile(outputPath) {
    let files = fs.readdirSync(outputPath);

    files.forEach((item) => {
        if (item !== 'uploads' && item != 'favicon.ico') {
            var fpath = path.resolve(outputPath, item),
                fstat = fs.statSync(fpath);

            if (fstat.isDirectory()) {
                rmGenFile(fpath);
                fs.rmdirSync(fpath)
            } else {
                fs.unlinkSync(fpath);
            }
        }
    });
}

rmGenFile(outputPath);

//生成入文件
let entry = {
    'app': `${srcPath}/index.js`,
}

let plugins = [
    new vueLoaderPlugin(),

    new htmlPlugin({
        filename: `${outputPath}/index.html`,
        template: `${srcPath}/index.html`,
        inject: 'body',
        chunksSortMode: 'auto',
    }),

    //不能写成common.css，打包的时候会缺少文件
    //https://github.com/lavas-project/lavas/issues/85
    new extractTextPlugin({
        filename: `css/[name][hash:4].css`,
        allChunks: true
    }),

    new happyPack({ //多线程打包js
        id: 'happybabel',
        loaders: ['babel-loader?cacheDirectory=true?presets=es2015'],
        threadPool: happyThreadPool,
        // cache: true,
        verbose: true
    }),
    new happyPack({ //多线程打包css
        id: 'postcss',
        loaders: ["css-loader?-autoprefixer!postcss-loader"],
        threadPool: happyThreadPool,
        // cache: true,
        verbose: true
    }),
    new happyPack({ //多线程打包less
        id: 'postless',
        //https://segmentfault.com/q/1010000009157879，注意编译顺序
        loaders: ['css-loader?-autoprefixer!postcss-loader!less-loader'],
        threadPool: happyThreadPool,
        // cache: true,
        verbose: true
    }),

    // new copyFile([
    //     //复制文件
    //     //ckeditor
    //     {
    //         from: `/${srcPath}/common/editor`,
    //         to: `${outputPath}/editor`,
    //         force: true,
    //     }
    // ]),

    new copyFile([
        //复制文件
        //favicon.ico
        {
            from: `${srcPath}/image/favicon.ico`,
            to: `${outputPath}`,
            force: true,
        }
    ]),
    
    //热更新
    // new webpack.HotModuleReplacementPlugin(),
    // new webpack.NoEmitOnErrorsPlugin(),
    // new webpack.NamedModulesPlugin()
]


module.exports = {
    performance: {
        hints: false
        // hints: "warning", // 枚举
        // maxAssetSize: 300000, // 整数类型（以字节为单位）
        // maxEntrypointSize: 500000, // 整数类型（以字节为单位）
        // assetFilter: function (assetFilename) {
        //     // 提供资源文件名的断言函数
        //     return assetFilename.endsWith('.css') || assetFilename.endsWith('.js');
        // }
    },

    entry,

    output: {
        path: `${outputPath}`,
        filename: '[name]/index[hash:4].js',
        chunkFilename: 'js/chunks/[name][hash:4].js',
        publicPath: '/'
    },

    // devServer: {
    //     contentBase: outputPath,
    //     compress: true,
    //     port: 9000,
    //     hot: true,
    //     inline: true,
    //     publicPath: `${outputPath}`,
    //     open: true,
    //     historyApiFallback:true,
    //     progress:true,
    // },

    // externals: {
    //     'CKEDITOR': 'window.CKEDITOR'
    // },

    // context: sourcePath,
    resolve: {
        //一定要添加，不然无法找到vue文件
        extensions: ['.js', '.vue', '.json', '.css', '.less'],
        alias: {
            'vue': 'vue/dist/vue.js',
            '@src': `${srcPath}`,
            '@css': `${srcPath}/css`,
            '@view': `${srcPath}/view`,
            '@component': `${srcPath}/component`
        }
    },

    optimization: {
        splitChunks: {
            chunks: 'all',
            minChunks: 2,
            minSize: 30000
        },
        runtimeChunk: true
    },

    plugins,

    module: {
        rules: [{
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    extractCSS: true,
                    loaders: {
                        css: extractTextPlugin.extract({
                            use: ["happypack/loader?id=postcss"],
                            fallback: 'vue-style-loader',
                        }),
                        less: extractTextPlugin.extract({
                            use: ["happypack/loader?id=postless"],
                            fallback: 'vue-style-loader'
                        }),
                    }
                }
            },
            {
                test: /\.js$/,
                loader: 'happypack/loader?id=happybabel', //已经在.babelrc配置，这里配置的话多线程任务会出错
                exclude: /node_modules/
            },
            {
                test: /\.css$/,
                use: extractTextPlugin.extract({ //单独打包
                    fallback: "style-loader", //一定要加fallback
                    use: ["happypack/loader?id=postcss"]
                }),
            },
            {
                test: /\.less$/,
                use: extractTextPlugin.extract({ //单独打包
                    fallback: 'style-loader', //一定要加fallback
                    use: ["happypack/loader?id=postless"]
                }),
            },
            {
                test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
                loader: 'url-loader',
                options: {
                    limit: 10000,
                    //删除 / 改写到public path，为了热重启能找到
                    name: `image/[name].[hash:7].[ext]`,
                }
            },
            {
                test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                loader: 'url-loader',
                options: {
                    limit: 10000,
                    //删除 / 改写到public path，为了热重启能找到
                    name: `fonts/[name].[hash:7].[ext]`,
                }
            }
        ],
    }
}