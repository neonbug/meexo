define([], function() {
	var exports = {};
	var app_data = {};
	
	function initLogin()
	{
		$('form').submit(function(e) {
			$('button[type="submit"]').addClass('loading');
		});
	}
	
	exports.init = function(trans, config) {
		app_data.trans = trans;
		app_data.config = config;
		
		$(document).ready(function() {
			initLogin();
		});
	};
	
	return exports;	
});
