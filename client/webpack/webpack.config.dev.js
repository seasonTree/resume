const baseWebpackConfig = require('./webpack.config.base');
const merge = require('webpack-merge');

module.exports = merge(baseWebpackConfig, {
    mode: "development", //运行环境
});
