import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import { isProd, strip, isDev } from './utils.js';

export default {
    optimization: strip({
        minimize: isProd()
    }),
    module: {
        rules: [
            {
                test: /\.s?css$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            url: false
                        }
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: [
                                    [ 'autoprefixer', { } ]
                                ]
                            }
                        }
                    },
                    {
                        loader: 'sass-loader',
                        options: {}
                    }
                ]
            },
            // WOFF Font
            // {
            //     test: /\.woff(\?v=\d+\.\d+\.\d+)?$/,
            //     type: 'asset/resource'
            // },
            // WOFF2 Font
            // {
            //     test: /\.woff2(\?v=\d+\.\d+\.\d+)?$/,
            //     type: 'asset/resource'
            // },
            // OTF Font
            // {
            //     test: /\.otf(\?v=\d+\.\d+\.\d+)?$/,
            //     type: 'asset/resource'
            // },
            // TTF Font
            // {
            //     test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/,
            //     type: 'asset/resource'
            // },
            // EOT Font
            // {
            //     test: /\.eot(\?v=\d+\.\d+\.\d+)?$/,
            //     type: 'asset/resource'
            // },
            // Common Image Formats
            // {
            //     test: /\.(?:ico|gif|png|jpg|jpeg|webp|svg)$/,
            //     type: 'asset/resource'
            // }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            runtime: true,
            filename: 'css/[name].[contenthash:8].css',
            chunkFilename: 'css/[name].[contenthash:8].css'
        })
    ]
};