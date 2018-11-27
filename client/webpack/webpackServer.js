const express = require('express');
const webpack = require('webpack');
const webpackConfig = require('./webpack.config.dev.js');
const webpackDevMiddleware = require('webpack-dev-middleware');
const webpackHotMiddleware = require('webpack-hot-middleware');
// const apiMocker = require('webpack-api-mocker');
const path = require('path');
// const mocker = require('../test/mocker/index');

const app = new express();

// https://www.jianshu.com/p/6f6588fddcea

//配置中间件
app.use(express.static(path.resolve(__dirname, '../dist')))

//配置webpack中间件

//注入socket监听文件变化
webpackConfig.entry.app = ['webpack-hot-middleware/client', webpackConfig.entry.app];

//加入hot replace
webpackConfig.plugins.push(new webpack.HotModuleReplacementPlugin());
webpackConfig.plugins.push(new webpack.NoEmitOnErrorsPlugin());
webpackConfig.plugins.push(new webpack.NamedModulesPlugin());

const compiler = webpack(webpackConfig);
app.use(webpackDevMiddleware(compiler, {
    publicPath: webpackConfig.output.publicPath
}));

//hot replacement
app.use(webpackHotMiddleware(compiler));

app.listen(9000, () => {
    console.log('list at http://localhost:9000')
});