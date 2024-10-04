const path = require('path');
const defaultConfig = require('./node_modules/@wordpress/scripts/config/webpack.config');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const isProduction = process.env.NODE_ENV === 'production';
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const globImporter = require('node-sass-glob-importer');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

const config = {
	...defaultConfig,
	plugins: [
		/**
		 * Remove previous instance
		 */
		...defaultConfig.plugins.filter(
			(plugin) =>
				plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'
		),
		new DependencyExtractionWebpackPlugin({
			requestToExternal: (request, external) => {
				if ('@qlse/backend' === request) {
					return ['qlse', 'backend'];
				}
				if ('@qlse/gutenberg' === request) {
					return ['qlse', 'gutenberg'];
				}
				if ('@qlse/store' === request) {
					return ['qlse', 'store'];
				}
				// Return the default value for other requests
				return external;
			},
			requestToHandle: (request, external) => {
				if ('@qlse/backend' === request) {
					return 'qlse-backend';
				}
				if ('@qlse/gutenberg' === request) {
					return 'qlse-gutenberg';
				}
				if ('@qlse/store' === request) {
					return 'qlse-store';
				}
				// Return the default value for other requests
				return external;
			},
		}),
	],
};

module.exports = [
	// Backend
	{
		...config,
		entry: {
			index: path.resolve(__dirname, 'packages', './backend/index.js'),
		},
		output: {
			filename: '[name].js',
			path: path.resolve(__dirname, 'build/backend/js/'),
			library: ['qlse', 'backend'],
			libraryTarget: 'window',
		},
		optimization: {
			minimize: isProduction,
		},
	},
	{
		...config,
		entry: {
			index: path.resolve(__dirname, 'packages', './backend/style.scss'),
		},
		output: {
			filename: '[name].js',
			path: path.resolve(__dirname, 'build/backend/css/'),
		},
		module: {
			...config.module,
			rules: [
				{
					test: /\.scss$/,
					use: [
						MiniCssExtractPlugin.loader,
						{
							loader: 'css-loader',
						},
						{
							loader: 'sass-loader',
							options: {
								sassOptions: {
									importer: globImporter(),
								},
							},
						},
					],
				},
			],
		},
		plugins: [
			new RemoveEmptyScriptsPlugin(),
			new MiniCssExtractPlugin({
				filename: 'style.css',
			}),
		],
	},
	//Gutenberg
	{
		...config,
		entry: {
			index: path.resolve(__dirname, 'packages', './gutenberg/index.js'),
		},
		output: {
			filename: '[name].js',
			path: path.resolve(__dirname, 'build/gutenberg/js/'),
			library: ['qlse', 'gutenberg'],
			libraryTarget: 'window',
		},
		optimization: {
			minimize: isProduction,
		},
	},
	// {
	// 	...config,
	// 	entry: {
	// 		index: path.resolve(__dirname, 'packages', './gutenberg/style.scss'),
	// 	},
	// 	output: {
	// 		filename: '[name].js',
	// 		path: path.resolve(__dirname, 'build/gutenbergrg/css/'),
	// 	},
	// 	module: {
	// 		...config.module,
	// 		rules: [
	// 			{
	// 				test: /\.scss$/,
	// 				use: [
	// 					MiniCssExtractPlugin.loader,
	// 					{
	// 						loader: 'css-loader',
	// 					},
	// 					{
	// 						loader: 'sass-loader',
	// 						options: {
	// 							sassOptions: {
	// 								importer: globImporter(),
	// 							},
	// 						},
	// 					},
	// 				],
	// 			},
	// 		],
	// 	},
	// 	plugins: [
	// 		new RemoveEmptyScriptsPlugin(),
	// 		new MiniCssExtractPlugin({
	// 			filename: 'style.css',
	// 		}),
	// 	],
	// },
	//Settings
	{
		...config,
		entry: {
			index: path.resolve(__dirname, 'packages', './settings/index.js'),
		},
		output: {
			filename: '[name].js',
			path: path.resolve(__dirname, 'build/settings/js/'),
			library: ['qlse', 'settings'],
			libraryTarget: 'window',
		},
		optimization: {
			minimize: isProduction,
		},
	},
	// {
	// 	...config,
	// 	entry: {
	// 		index: path.resolve(__dirname, 'packages', './settings/style.scss'),
	// 	},
	// 	output: {
	// 		filename: '[name].js',
	// 		path: path.resolve(__dirname, 'build/settings/css/'),
	// 	},
	// 	module: {
	// 		...config.module,
	// 		rules: [
	// 			{
	// 				test: /\.scss$/,
	// 				use: [
	// 					MiniCssExtractPlugin.loader,
	// 					{
	// 						loader: 'css-loader',
	// 					},
	// 					{
	// 						loader: 'sass-loader',
	// 						options: {
	// 							sassOptions: {
	// 								importer: globImporter(),
	// 							},
	// 						},
	// 					},
	// 				],
	// 			},
	// 		],
	// 	},
	// 	plugins: [
	// 		new RemoveEmptyScriptsPlugin(),
	// 		new MiniCssExtractPlugin({
	// 			filename: 'style.css',
	// 		}),
	// 	],
	// },
	//Store
	{
		...config,
		entry: {
			index: path.resolve(__dirname, 'packages', './store/index.js'),
		},
		output: {
			filename: '[name].js',
			path: path.resolve(__dirname, 'build/store/js/'),
			library: ['qlse', 'store'],
			libraryTarget: 'window',
		},
		optimization: {
			minimize: isProduction,
		},
	},
];
