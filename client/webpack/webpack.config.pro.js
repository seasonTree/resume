const baseWebpackConfig = require('./webpack.config.base');
//压缩css
const optimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const uglifyjs = require('uglifyjs-webpack-plugin');
const merge = require('webpack-merge');
const cssnano = require('cssnano');
const postSafeParse = require('postcss-safe-parser');

module.exports = merge(baseWebpackConfig, {
    mode: 'production',
    plugins: [
        new uglifyjs(),

        // https://zhuanlan.zhihu.com/p/37251575
        new optimizeCssAssetsPlugin({ //压缩css 
            assetNameRegExp: /\.css\.*(?!.*map)/g,
            cssProcessor: cssnano,
            parser: postSafeParse,
            cssProcessorOptions: {
                discardComments: {
                    removeAll: true
                },
                // 避免 cssnano 重新计算 z-index
                // safe: true,
                // cssnano 集成了autoprefixer的功能
                // 会使用到autoprefixer进行无关前缀的清理
                // 关闭autoprefixer功能
                // 使用postcss的autoprefixer功能
                autoprefixer: false
            },
            canPrint: true
        }),
    ]
})