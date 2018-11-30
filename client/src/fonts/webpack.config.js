const webpackDevServer = require('webpack-dev-server');
const webpack = require("webpack");
const path = require('path');
module.exports = {
    entry:{
        index:'./src/js/index.js',
        goodsInfo:'./src/js/goodsInfo.js'
    },
    output:{
        filename:'[name].js',
        path:path.resolve(__dirname,'out'),
        publicPath: 'http://localhost:9999/out',
        // path:__dirname +'/out',
    },
    //开启web服务器
    // devServer:{
    //     contentBase:'./',
    //     historyApiFallback:true,
    //     hot:true,
    //     progress:true,
    //     inline:true,
    //     port:'9999'
    // },
    module:{
        rules:[
            {test:/\.css$/,use:['style-loader','css-loader']},
            {test:/\.(png|jpg|svg|ttf|woff|eot)$/,use:['url-loader']},
            {test:/\.js$/,use:['babel-loader']}
        ]
    },
    plugins:[
        // new webpack.HotModuleReplacementPlugin()
    ],
    mode:'development'
}