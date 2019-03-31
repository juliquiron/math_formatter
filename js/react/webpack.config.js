var webpack = require('webpack');
var path = require('path');

var MODULE_BUILD_DIR = path.resolve(__dirname, 'build');
var MODULE_APP_DIR = path.resolve(__dirname);

var config = {
  entry: MODULE_APP_DIR + '/index.js',
  output: {
    path: MODULE_BUILD_DIR,
    filename: 'math_formatter.js'
  },
  module: {
    rules: [
      {
        test: /\.(js)$/,
        exclude: /node_modules/,
        use: [
          {
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-react']
            }
          }
        ]
      }
    ]
  }
};

module.exports = config;
