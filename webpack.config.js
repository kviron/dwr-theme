const path = require('path');
const webpack = require('webpack');
const dotEnv = require('dotenv').config()
const TerserPlugin = require("terser-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const SpriteLoaderPlugin = require('svg-sprite-loader/plugin');
const WebpackAssetsManifest = require('webpack-assets-manifest');
const FriendlyErrorsWebpackPlugin = require('friendly-errors-webpack-plugin');
const minify = require('optimize-css-assets-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

const mode = process.env.NODE_ENV;
const isDev = mode === 'development'

module.exports = {
    entry: {
        app: './index.js'
    },
    mode: mode && 'development',
    context: path.resolve(__dirname, '_src'),
    output: {
        path: path.resolve(__dirname, 'assets'),
        publicPath: path.resolve(__dirname, 'assets'),
        filename: 'js/[name].[contenthash].js',
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
                new minify({}),
                new TerserPlugin({
                    test: /\.js(\?.*)?$/i,
                    parallel: 4,
                })
            ],
        } : [])
    },
    devtool: 'inline-source-map',
    devServer: {
        static: './assets',
        hot: true,
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
        ],
        alias: {
            '@files': path.resolve(__dirname, '_src/files'),
            '@images': path.resolve(__dirname, '_src/images'),
            '@icons': path.resolve(__dirname, '_src/files/icons'),
            '@scripts': path.resolve(__dirname, '_src/scripts'),
            '@styles': path.resolve(__dirname, '_src/styles'),
        }
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
                                loader: 'style-loader',
                            },
                            {
                                loader: 'cache-loader'
                            }
                        ] : [MiniCssExtractPlugin.loader]
                    ),
                    {
                        loader: 'css-loader',
                        options: {sourceMap: isDev}
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
                test: /\.svg$/,
                loader: 'svg-sprite-loader',
                options: {
                    extract: true,
                    spriteFilename: svgPath => `sprite.svg`,
                    symbolId: filePath => path.basename(filePath)
                }
            }
        ]
    },
    plugins: [
        ...(isDev ? [
            new BrowserSyncPlugin({
                proxy: 'http://sage.loc',
                files: ['**/*.php'],
                injectCss: true,
            }, { reload: true, }),
            new webpack.HotModuleReplacementPlugin(),
        ] : []),
        new CleanWebpackPlugin(),
        new CopyPlugin({
            patterns: [
                {from: "files", to: "./files"},
            ],
        }),
        new MiniCssExtractPlugin({
            filename: 'css/[name].[contenthash].css'
        }),
        new WebpackAssetsManifest({
            output: 'assets.json',
            space: 2,
            writeToDisk: true,
            assets: {},
        }),
        new SpriteLoaderPlugin({
            plainSprite: true
        })
    ],
}