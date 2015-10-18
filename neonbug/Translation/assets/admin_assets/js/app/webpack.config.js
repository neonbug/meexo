var webpack = require('webpack');
var path = require('path');

module.exports = {
	entry: {
		translation_add:  './src/translation_add'
	}, 
	resolve: {
		alias: { }
	}, 
	output: {
		path: './',
		filename: '[name].js'
	}
}
