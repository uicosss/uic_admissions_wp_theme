import { strip, isDev } from './utils.js';

export default {
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: strip({
                        sourceMaps: isDev('both', false),
                        // cacheDirectory: false,
                        // cacheCompression: false,
                        presets: [
                            [
                                '@babel/preset-env', 
                                {
                                    modules: false,
                                    targets: 'defaults'
                                }
                            ],
                            '@babel/preset-typescript'
                        ],
                        plugins: strip([
                            ['@babel/plugin-transform-runtime', strip({
                                useESModules: false,
                                helpers: true
                            })],
                            '@babel/plugin-proposal-class-properties',
                            '@babel/plugin-proposal-object-rest-spread'
                        ])
                    })
                }
            }
        ]
    },
    resolve: {
        extensions: ['.ts', '.js']
    }
};