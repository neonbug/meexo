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

	__webpack_require__(96);


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

/***/ 96:
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(global) {module.exports = global["list"] = __webpack_require__(97);
	/* WEBPACK VAR INJECTION */}.call(exports, (function() { return this; }())))

/***/ },

/***/ 97:
/***/ function(module, exports, __webpack_require__) {

	var global = __webpack_require__(3);

	module.exports = {};

	var app_data = {};

	function initDelete()
	{
		if (app_data.config.delete_route != null)
		{
			$('.delete-item').click(function() {
				var id_item = this.dataset.idItem;
				var modal = $('.delete-item-modal');
				
				modal.modal({
					blurring: true, 
					onApprove: function() {
						$('.ui.ok', modal).addClass('loading');
						
						$.post(app_data.config.delete_route, { id: id_item }, function(data) {
							//TODO check data.success
							//TODO also check for generic errors, like TokenMismatch (should catch it in Error handler of ajax response); do sth smart in that case .. like .. reload?
							
							modal.modal('hide');
							$('.ui.ok', modal).removeClass('loading');
							
							global.showToast('success', app_data.trans.messages.deleted);
							setTimeout(function() { document.location.reload(); }, 3000);
						}, 'json');
						
						return false;
					}
				}).modal('show');
			});
		}
	}

	module.exports.init = function(trans, config) {
		app_data.trans = trans;
		app_data.config = config;
		
		$(document).ready(function() {
			initDelete();
		});
	};


/***/ }

/******/ });