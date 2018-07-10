const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = {
    entry: {
        app: './assets/javascript/app.js',
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'public/dist')
    },
    devtool: 'inline-source-map',
    mode: 'development',
    watchOptions: {
        aggregateTimeout: 300,
        poll: 1000,
        ignored: /node_modules/
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].css",
        }),
        new CopyWebpackPlugin([
            {from: './assets/images/', to: 'img/', toType: 'dir'}
        ], {debug: 'info'})
    ],
    module: {
        rules: [
            {
                test: /\.(css|scss)$/,
                use: [
                    // Adds CSS to the DOM by injecting a `<style>` tag
                    {loader: 'style-loader'},

                    // Extract CSS from javascript loader
                    MiniCssExtractPlugin.loader,

                    // Interprets `@import` and `url()` like `import/require()` and will resolve them
                    {loader: 'css-loader'},

                    // Loader for webpack to process CSS with PostCSS
                    {
                        loader: 'postcss-loader',
                        options: {
                            plugins: function () {
                                return [
                                    require('autoprefixer')
                                ];
                            }
                        }
                    },

                    // Loads a SASS/SCSS file and compiles it to CSS
                    {loader: 'sass-loader'}
                ]
            },
            {
                test: /\.(png|svg|jpg|gif)$/,
                use: ['file-loader']
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                use: ['file-loader']
            }
        ]
    }
};