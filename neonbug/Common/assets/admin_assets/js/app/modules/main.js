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
	
	function initTokenChecking() {
		startTokenChecking();
	}
	
	var token_interval = -1;
	function startTokenChecking() {
		token_interval = setInterval(checkToken, 20000);
	}
	
	function stopTokenChecking() {
		if (token_interval != -1)
		{
			clearInterval(token_interval);
			token_interval = -1;
		}
	}
	
	var current_login_modal = null;
	
	function checkToken() {
		$.post(app_data.config.check_token_route, function(data) {
			if (data == true) return; //we're still logged in
			
			stopTokenChecking();
			
			var modal = $($('.login-modal-template').html());
			$('body').append(modal);
			
			current_login_modal = modal;
			
			modal.modal({
				blurring: true, 
				closable: false
			}).modal('show');
			
			var submit = $('.login-button', modal);
			submit.click(function() {
				$('.login-button', modal).addClass('loading');
				
				loginUser(
					$('[name="username"]', modal).val(), 
					$('[name="password"]', modal).val(), 
					{
						success: function() {
							global.showToast('success', app_data.trans.messages.logged_in);
						}, 
						failure: function() {
							$('.login-button', modal).removeClass('loading');
						}
					}
				);
			});
			
			$('.field', modal).keyup(function(e) {
				if (e.which == 13)
				{
					submit.click();
				}
			});
		}, 'json');
	}
	
	function loginUser(username, password, options)
	{
		$.get(app_data.config.token_route, function(token) {
			$('meta[name="csrf_token"').attr('content', token.encrypted_token);
			
			$.post(app_data.config.login_route, { username: username, password: password }, function(data) {
				if (data.success === true)
				{
					if (current_login_modal != null)
					{
						$('.field .input', current_login_modal).val('');
						
						current_login_modal.modal('hide');
						current_login_modal.remove();
					}
					startTokenChecking();
					
					$('[name="_token"]').val(data.token);
					$('meta[name="csrf_token"').attr('content', token.encrypted_token);
					
					if (options.success != undefined) options.success();
				}
				else
				{
					if (options.failure != undefined) options.failure();
				}
			}, 'json').fail(function(jqXHR, textStatus, errorThrown) {
				var errors = jqXHR.responseJSON;
				
				$('.field .input', current_login_modal).removeClass('error');
				$('.error.message', current_login_modal).addClass('hidden');
				$('.error.message .content', current_login_modal).html('');
				
				for (var field_name in errors)
				{
					$('[data-name="' + field_name + '"]', current_login_modal).addClass('error');
					$('.error.message .content', current_login_modal).append($('<p />').text(errors[field_name]));
				}
				
				if (Object.keys(errors).length > 0)
				{
					$('.error.message', current_login_modal).removeClass('hidden');
				}
				
				if (options.failure != undefined) options.failure();
			});
		}, 'json');
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
			initTokenChecking();
		});
	};
	
	return exports;	
});
