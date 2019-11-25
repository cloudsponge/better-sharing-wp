const webpack = require('webpack');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const configBlocks = {
	watch: true,
  entry: ['./blocks/index.js'],
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'blocks/blocks.bundle.js'
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        use: 'babel-loader',
        exclude: /node_modules/
      },
      {
        test: /\.scss$/,
        use: [
          'style-loader',
          'css-loader',
          'sass-loader',
        ]
      }
    ]
  },
  resolve: {
    extensions: [
      '.js',
      '.jsx'
    ]
  }
};

const configAdmin = {
	watch: true,
	entry: ['./admin-assets/admin.js'],
	output: {
		path: path.resolve(__dirname, 'dist'),
		filename: 'admin/admin.bundle.js'
	},
	module: {
		rules: [
			{
				test: /\.(js|jsx)$/,
				use: 'babel-loader',
				exclude: /node_modules/
			},
			{
				test: /\.scss$/,
				use: [
					'style-loader',
					'css-loader',
					'sass-loader',
				]
			}
		]
	},
	resolve: {
		extensions: [
			'.js',
			'.jsx'
		]
	}
};

module.exports = [configBlocks, configAdmin];