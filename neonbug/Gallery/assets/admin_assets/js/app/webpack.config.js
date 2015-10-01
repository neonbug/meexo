var webpack = require('webpack');
var path = require('path');

module.exports = {
	entry: {
		gallery_images:  './src/gallery_images'
	}, 
	resolve: {
		alias: { }
	}, 
	output: {
		path: './',
		filename: '[name].js'
	}
}
