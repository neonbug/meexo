var webpack = require('webpack');
var path = require('path');

module.exports = {
	entry: {
		main:      './src/main', 
		list:      './src/list', 
		add:       './src/add', 
		login:     './src/login', 
		dashboard: './src/dashboard'
	}, 
	resolve: {
		alias: {
			moment:      path.join(__dirname, 'src/deps/moment.js'), 
			speakingurl: path.join(__dirname, 'src/deps/speakingurl.min.js'), 
			pikaday:     path.join(__dirname, 'src/deps/pikaday/pikaday.js')
		}
	}, 
	output: {
		path: './',
		filename: '[name].js'
	}
}
