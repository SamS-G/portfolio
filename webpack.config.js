const webpack = require('webpack')
const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');

module.exports = {
    mode: "production",
    entry: './src/index.js',
    output: {
        filename: 'scripts/[name].js',  // Met le script dans dans un dossier //
        path: path.resolve(__dirname, 'dist'),
        assetModuleFilename: 'resources/[hash][ext][query]',   // Met les images dans un dossier //
        chunkFilename: '[name].js',
        clean: true,
    },
    optimization: {
        minimize: true,
        nodeEnv: 'production',
        splitChunks:{ chunks: "all"},
    },
    module: {
        rules: [
            {
                test: /\.css$/i,
                use: [
                    'style-loader',
                    'css-loader'
                ],
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/i,
                type: "asset/resource"
            },
            {
                test: /\.html$/,
                use: {
                    loader: "html-loader"
                }
            },
            {
                test: /\.(png|svg|jpg|jpeg|gif|webp)$/i,
                type: 'asset/resource',
            },
            {
                test: /\.m?js$/,
                exclude: /(node_modules|bower_components)/,
                use: [
                    {
                        loader: "babel-loader",
                        options: {
                            presets: [
                                '@babel/preset-env',
                            ],

                        }
                    }
                ]
            },
        ]
    },
    plugins: [
        new HtmlWebpackPlugin({
            inject: 'body',
            template: "./src/index.html",
            filename: "index.html",
            cache: true
        }),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
        })
    ]
};