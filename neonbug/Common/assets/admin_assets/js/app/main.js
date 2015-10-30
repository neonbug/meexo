/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ({

/***/ 0:
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(100);


/***/ },

/***/ 3:
/***/ function(module, exports) {

	module.exports = {};

	function showToast(type, message)
	{
		noty({
			layout: 'customTopRight', 
			type: type, 
			text: message, 
			timeout: 5000, 
			theme: 'custom_relax', 
			animation: {
				open: 'animated bounceInDown', // Animate.css class names
				close: 'animated fadeOutUp' // Animate.css class names
			}
		});
	}

	module.exports.showToast = function(type, message) {
		showToast(type, message);
	};


/***/ },

/***/ 100:
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(global) {module.exports = global["main"] = __webpack_require__(101);
	/* WEBPACK VAR INJECTION */}.call(exports, (function() { return this; }())))

/***/ },

/***/ 101:
/***/ function(module, exports, __webpack_require__) {

	var global = __webpack_require__(3);

	module.exports = {};

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

	function initSidebar()
	{
		$('#open-menu').click(function()
		{
			$('.main-menu.ui.sidebar').sidebar({ dimPage: false }).sidebar('toggle');
		});

		$(window).resize(function()
		{
			if($(window).width() > 767) // desktop
			{
				var sidebar = $('.main-menu.ui.sidebar');
				if (sidebar.sidebar('is visible'))
				{
					sidebar.sidebar('hide');
				}
			}
			else // mobile
			{
			}
		});
	}

	module.exports.init = function(trans, config) {
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
			initSidebar();
		});
	};


/***/ }

/******/ });