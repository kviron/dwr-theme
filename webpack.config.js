const path = require('path');
const webpack = require('webpack');
const dotEnv = require('dotenv').config()
const TerserPlugin = require("terser-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const SpriteLoaderPlugin = require('svg-sprite-loader/plugin');
const WebpackAssetsManifest = require('webpack-assets-manifest');
const CopyPlugin = require('copy-webpack-plugin');

const context = 'src';
const isDev = process.env.NODE_ENV === 'development'

module.exports = {
    entry: {
        app: './index.js',
    },
    context: path.resolve(__dirname, 'src'),
    output: {
        path: path.resolve(__dirname, 'assets'),
        filename: 'js/[name].[hash].js',
    },
    optimization: {
        splitChunks: {
            cacheGroups: {
                vendor: {
                    name: 'vendors',
                    test: /[\\/]node_modules[\\/]/,
                    chunks: 'all',
                    enforce: true
                }
            }
        },
        ...(!isDev ? {
            minimize: true,
            minimizer: [
                new TerserPlugin({
                    test: /\.js(\?.*)?$/i,
                    parallel: 4,
                })
            ],
        } : [])
    },
    resolve: {
        extensions: [
            '.js',
            '.jsx',
            '.css',
            '.scss',
            '.jpg',
            '.jpeg',
            '.png',
            '.svg',
        ]
    },
    module: {
        rules: [
            {
                test: /\.m?js$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: "babel-loader",
                        options: {
                            presets: ['@babel/preset-env']
                        }
                    }
                ]
            },
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    ...(isDev ? [
                        {
                            loader: 'style-loader'
                        },
                        {
                            loader: 'cache-loader'
                        }
                    ] : [MiniCssExtractPlugin.loader]),
                    {
                        loader: 'css-loader', options: {sourceMap: isDev}
                    },
                    {
                        loader: "postcss-loader",
                        options: {
                            sourceMap: isDev,
                            postcssOptions: {
                                plugins: [
                                    [
                                        "postcss-preset-env",
                                        {
                                            // Options
                                        },
                                    ],
                                ],
                            },
                        },
                    },
                    {
                        loader: "resolve-url-loader",
                        options: {sourceMap: true}
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true
                        }
                    }
                ],
            },
            {
                test: /\.(png|jpg|svg|gif)$/,
                exclude: /(icons)/,
                use: ['file-loader']
            },
            {
                test: /\.(ttf|woff|woff2|eot)$/,
                exclude: /(icons)/,
                use: ['file-loader']
            },
            {
                test: /\.svg$/i,
                include: /.*\.svg/,
                use: [
                    {
                        loader: 'svg-sprite-loader',
                    },
                ],
            }
        ]
    },
    plugins: [
        new SpriteLoaderPlugin({
            plainSprite: true
        }),
        new CleanWebpackPlugin(),
        new MiniCssExtractPlugin({
            filename: 'css/[name].[hash].css'
        }),
        new WebpackAssetsManifest({
            output: 'assets.json',
            space: 2,
            writeToDisk: true,
            assets: {},
        }),
        new CopyPlugin({
            patterns: [
                { from: "files", to: "./" },
            ],
        }),
    ],
}