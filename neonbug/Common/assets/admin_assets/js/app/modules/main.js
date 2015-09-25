define([ 'global' ], function(global) {
	var exports = {};
	var app_data = {};
	
	var content_changed = false;
	function hasContentChanged() { return content_changed; }
	function setContentChanged(value) { content_changed = value; }
	
	function initCloseWarning() {
		$(window).on('beforeunload', function(e) {
			if (!hasContentChanged()) return;
			
			//TODO somehow check if the inputs are *really* different than initial (e.g. a diff against initial state)
			
			var message = app_data.trans.messages.close_page;
			
			e.returnValue = message;
			return message;
		});
		
		$('.add input, .add textarea').change(function() { setContentChanged(true); });
		//TODO maybe save all input values here, for diffing above?
	}
	
	exports.init = function(trans, config) {
		app_data.trans = trans;
		app_data.config = config;
		
		$.ajaxPrefilter(function(options, originalOptions, xhr) {
			var token = $('meta[name="csrf_token"]').attr('content');

			if (token) {
				return xhr.setRequestHeader('X-XSRF-TOKEN', token);
			}
		});
		
		$(document).ready(function() {
			//initCloseWarning();
		});
	};
	
	return exports;	
});
