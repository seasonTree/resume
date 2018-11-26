module.exports = {
    plugins: [
        //http://browserl.ist/?q=last+10+versions
        //兼容最近2个版本呢
        require('autoprefixer')({browsers: ['last 5 versions'] })
        // require('autoprefixer')({browsers: ['last 2 versions'] }),
    ]
};