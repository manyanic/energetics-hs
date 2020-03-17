const proxy = 'https://energetics.local/'
const themeName = 'hs-starter-theme'
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
const path = require('path')
const webpack = require('webpack')
const SimpleProgressWebpackPlugin = require('simple-progress-webpack-plugin')
const HardSourceWebpackPlugin = require('hard-source-webpack-plugin')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const TerserPlugin = require('terser-webpack-plugin')
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const disableReload = process.env.DISABLE_RELOAD === '1'
module.exports = {
  entry: [
    './src/main.js'
  ],
  output: {
    publicPath: '/wp-content/themes/' + themeName + '/',
    path: path.resolve(__dirname),
    filename: 'js/scripts.js',
    chunkFilename: 'js/[name].bundle.js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        use: ['babel-loader'],
        include: [
          path.resolve(__dirname, 'src')
        ]
      },
      {
        test: /\.(sa|sc|c)ss$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
          },
          'css-loader',
          'postcss-loader', // postcss-loader includes css vendor prefixing
          'sass-loader'
        ]
      },
      {
        test: /\.(ttf|eot|woff|woff2)$/,
        loader: 'file-loader',
        options: {
          name: 'wp-content/themes/' + themeName + '/fonts/[name].[ext]'
        }
      },
      {
        test: /\.(jpg|png|svg)$/,
        loader: 'file-loader',
        options: {
          name: 'wp-content/themes/' + themeName + '/images/[name].[ext]'
        }
      },
      {
        // set up standard-loader as a preloader
        enforce: 'pre',
        test: /\.js?$/,
        loader: 'standard-loader',
        exclude: /(node_modules|bower_components)/,
        options: {
          // Emit errors instead of warnings (default = false)
          error: false,
          // enable snazzy output (default = true)
          snazzy: true,
          parser: 'babel-eslint'
        }
      }
    ]
  },
  optimization: { // JS optimization by webpack during prod builds
    minimizer: [
      new TerserPlugin({
        test: /\.js(\?.*)?$/i,
        parallel: true
      }),
    ],
  },
  plugins: [
    new BrowserSyncPlugin({
      host: 'localhost',
      port: 3000,
      proxy: proxy,
      files: [
        {
          match: [
            '**/*.php'
          ],
          fn: function (event, file) {
            if (event === 'change') {
              const bs = require('browser-sync').get('bs-webpack-plugin')
              if (!disableReload) {
                console.log('RELOAD')
                bs.reload()
              }
            }
          }
        }
      ]
    }),
    new SimpleProgressWebpackPlugin({
      format: 'compact'
    }),
    new webpack.ProvidePlugin({ // Can be used to automatically load jquery as $
    }),
    new HardSourceWebpackPlugin(), // Caches and improves compilation during development
    new MiniCssExtractPlugin({ // Extracts CSS into separate files
      filename: 'style.css',
      chunkFilename: '[id].css'
    }),
    new OptimizeCssAssetsPlugin({ // Goto plugin for css minimising until webpack 5 arrives
      cssProcessor: require('cssnano')
    })
  ]
}
