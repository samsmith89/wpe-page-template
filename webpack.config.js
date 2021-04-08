const path = require("path");

module.exports = {
	entry: [
		"./src/scripts/index.js",
		"./src/styles/main.scss"
	],
	output: {
		filename: "main.js",
		path: path.resolve(__dirname, "dist")
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: [],
			}, {
				test: /\.scss$/,
				exclude: /node_modules/,
				use: [
					{
						loader: 'file-loader',
						options: { outputPath: 'css/', name: '[name].min.css'}
					},
					'sass-loader'
				]
			}
		]
	}
}
