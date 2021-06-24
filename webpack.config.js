const { join } = require('path');
const { VueLoaderPlugin } = require('vue-loader');
const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

module.exports = {
    entry: {
        core: './src/core.js',
        login: './src/pages/login.js',
        userindex: './src/pages/userindex.js',
        usergetid: './src/pages/usergetid.js',
        pembinamutuindex: './src/pages/pembinamutuindex.js',
        pembinamutugetid: './src/pages/pembinamutugetid.js',
        upiindex: './src/pages/upiindex.js',
        upigetid: './src/pages/upigetid.js',
        upicreate: './src/pages/upicreate.js',
        upiedit: './src/pages/upiedit.js',
        dashboard: './src/pages/dashboard.js',
        laporanindex: './src/pages/laporanindex.js',
        laporandetail: './src/pages/laporandetail.js',
        laporancreate: './src/pages/laporancreate.js'
    },
    output: {
        path: join(__dirname, './public/dist'),
        filename: '[name].js'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                loader: 'babel-loader',
                options: {
                    presets: ['@babel/preset-env'],
                    plugins: ['transform-regenerator']
                }
            }, {
                test: /.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.css$/,
                use: [
                    MiniCSSExtractPlugin.loader,
                    'css-loader'
                ]
            },
            {
                test: /\.s[ac]ss$/,
                use: [
                    MiniCSSExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ]
            },
            {
                test: /\.(woff|woff2|eot|ttf|svg)(\?.*$|$)/,
                loader: 'file-loader'
            }
        ]
    },
    plugins: [
        new CleanWebpackPlugin(),
        new MiniCSSExtractPlugin(),
        new VueLoaderPlugin(),
    ],
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js'
        },
        extensions: ['*', '.js', '.vue', '.json']
    },
};