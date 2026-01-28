import webpack from 'webpack';
import { resolve } from 'path';
import ManifestPlugin from 'webpack-manifest-plugin';
import ForkTsCheckerWebpackPlugin from 'fork-ts-checker-webpack-plugin';

import { DIST_DIR, SCRIPTS_DIR, MODE, PUBLIC_BASE, ROOT_DIR, NODE_ENV } from './constants.js';
import babelCfg from './babel.config.js';
import assetCfg from './assets.js';
import { strip, isDev, isProd, omitEmpty } from './utils.js';

export default {
    ...babelCfg,
    ...assetCfg,
    mode: MODE,
    entry: {
        client: strip([
            isDev(resolve(SCRIPTS_DIR, 'browser-sync.ts')),
            resolve(SCRIPTS_DIR, 'index.ts'),
        ]),
        panini: strip([
            isDev(resolve(SCRIPTS_DIR, 'browser-sync.ts')),
            resolve(SCRIPTS_DIR, 'panini.ts')
        ])

    },
    devtool: isDev('inline-source-map', 'source-map'),
    target: 'web',
    module: {
        rules: [
            ...babelCfg.module.rules,
            ...assetCfg.module.rules
        ]
    },
    optimization: strip({
        runtimeChunk: 'single',
        splitChunks: {
            cacheGroups: {
                vendor: {
                    name: 'vendor',
                    test: /[\\/]node_modules[\\/]/,
                    chunks: 'all'
                }
            }
        },
        minimizer: omitEmpty([
            ...(assetCfg.optimization.minimizer || [])
        ])
    }),
    output: {
        path: DIST_DIR,
        publicPath: PUBLIC_BASE,
        uniqueName: 'uic',
        filename: 'js/[name].[contenthash:8].js',
        chunkFilename: 'js/[name].[contenthash:8].js'
    },
    resolve: {
        ...babelCfg.resolve,
        alias: {
            process: 'process/browser'
        },
        fallback: {
            process: false,
            util: false
        },
        modules: [ SCRIPTS_DIR, 'node_modules'],
        mainFields: ['module', 'main'],
    },
    experiments: {
        lazyCompilation: false
    },
    devServer: {
        hot: false,
        client: false
    },
    plugins: strip([
        ...assetCfg.plugins,
        isDev(new ForkTsCheckerWebpackPlugin({
            async: true,
            typescript: {
                configFile: resolve(ROOT_DIR, 'tsconfig.json')
            }
        })),
        new ManifestPlugin.WebpackManifestPlugin({
            publicPath: ''
        }),
        new webpack.DefinePlugin({
            'process.env.NODE_ENV': JSON.stringify(NODE_ENV)
        }),
        new webpack.ProvidePlugin({
            process: 'process/browser',
        })
    ]),
    node: {
        __dirname: false,
        __filename: false
    }
};