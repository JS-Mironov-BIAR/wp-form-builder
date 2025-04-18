const path = require('path')
const webpack = require('webpack')
const fs = require('fs')
// eslint-disable-next-line import/no-extraneous-dependencies
const dotenv = require('dotenv')

const envExists = fs.existsSync(path.resolve(__dirname, '.env'))

const env = envExists ? dotenv.config().parsed : {}

const NODE_ENV = env?.NODE_ENV || 'production'
const isProduction = NODE_ENV === 'production'

module.exports = {
    entry: {
        main: './assets/src/index.js',
        admin: './assets/src/admin/index.js',
        form: './assets/src/admin/form/index.js',
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'assets/dist'),
    },
    mode: isProduction ? 'production' : 'development',
    devtool: isProduction ? false : 'source-map',
    optimization: {
        minimize: isProduction,
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                include: path.resolve(__dirname, 'assets/src'),
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env'],
                    },
                },
            },
        ],
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env.NODE_ENV': JSON.stringify(NODE_ENV),
        }),
    ],
}
