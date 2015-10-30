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
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(1);


/***/ },
/* 1 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(global) {module.exports = global["gallery_images"] = __webpack_require__(2);
	/* WEBPACK VAR INJECTION */}.call(exports, (function() { return this; }())))

/***/ },
/* 2 */
/***/ function(module, exports) {

	module.exports = {};

	var app_data = {};

	function init()
	{
		$('.gallery-images-list').sortable({ 
			handle: 'image', 
			forcePlaceholderSize: true, 
			placeholder: '<div class="ui card gallery-images-image"></div>'
		});
		
		$('.field-gallery-images .field').each(function(idx, el) {
			var upload_dir = Math.floor(Math.random() * (1000000 - 1)) + 1;
			
			var flow = new Flow({
				target: app_data.config.upload_gallery_file_route.replace('UPLOAD_DIR', upload_dir), 
				headers: { }
			});
			
			flow.assignDrop($('.gallery-images-drop-target', el).get(0));
			flow.assignBrowse($('.gallery-images-browse', el).get(0));
			
			$('.gallery-images-drop-target', el)
				.on('dragenter', function() {
					$(this).addClass('drag-over');
				})
				.on('dragleave dragend drop', function() {
					$(this).removeClass('drag-over');
				});
			
			flow.on('filesSubmitted', function(file) {
				flow.opts.headers['X-XSRF-TOKEN'] = $('meta[name="csrf_token"]').attr('content');
				
				flow.upload();
			});
			
			flow.on('fileAdded', function(file) {
				var item = $($('#gallery-images-image-template').html())
					.appendTo($('.gallery-images-list', el));
				
				item.get(0).dataset.id = file.uniqueIdentifier;
				
				$('.gallery-images-image-name', item).text(file.name);
				
				$('.gallery-images-image-cancel', item).on('click', function () {
					file.cancel();
					item.remove();
				});
				
				reloadSortable();
				scrollToBottom(el);
			});
			
			flow.on('fileProgress', function(file) {
				var item = $('.gallery-images-image[data-id="' + file.uniqueIdentifier + '"]', el);
				
				var percent_value = Math.floor(file.progress()*100);
				
				$('.gallery-images-image-progress', item).text(percent_value + ' %');
				$('.gallery-images-image-upload-overlay-progress', item).css('height', percent_value + '%');
			});
			
			flow.on('fileSuccess', function(file) {
				var item = $('.gallery-images-image[data-id="' + file.uniqueIdentifier + '"]', el);
				
				var image_small_url = app_data.config.temp_small_image_template_url;
				var image_url = app_data.config.temp_image_template_url;
				
				var filename = file.name;
				var ext = '';
				var pos = filename.lastIndexOf('.');
				if (pos > -1)
				{
					ext = filename.substring(pos+1);
					filename = filename.substring(0, pos);
				}
				
				var item_image = $('.image', item);
				item_image.css('background-image', 
					'url("' + image_small_url
						.replace('{FILENAME}', filename)
						.replace('{EXT}', ext)
						.replace('{UPLOAD_DIR}', upload_dir)
					 + '")');
				item_image.attr('href', image_url
					.replace('{FILENAME}', filename)
					.replace('{EXT}', ext)
					.replace('{UPLOAD_DIR}', upload_dir)
				);
				
				var input_hidden = $('input[type="hidden"]', item);
				input_hidden.attr('name', el.dataset.name + '[images][]');
				input_hidden.val(upload_dir + '/' + file.name);
				
				$('.gallery-images-image-upload-overlay', item_image).remove();
				$('.gallery-images-image-upload-overlay-progress', item_image).remove();
				
				$('.gallery-images-image-cancel', item).removeClass('gallery-images-image-cancel')
					.addClass('gallery-images-image-remove')
					.off('click')
					.on('click', function() {
						$(this).parents('.gallery-images-image').remove();
					});
			});
			
			flow.on('fileError', function(file, message) {
				file.cancel();
				$('.gallery-images-image[data-id="' + file.uniqueIdentifier + '"]', el).remove();
				
				$('<div class="ui small modal">' + 
					'<div class="content">' + 
						app_data.trans.errors.upload_error + 
					'</div>' + 
					'<div class="actions"><div class="ui cancel button red">' + 
						app_data.trans.errors.upload_error_close + 
					'</div></div>' + 
				'</div>').modal('show');
			});
		});
		
		$('.gallery-images-image-remove').on('click', function() {
			$(this).parents('.gallery-images-image').remove();
		});
	}

	function reloadSortable()
	{
		$('.gallery-images-list').sortable();
	}

	function scrollToBottom(field)
	{
		var list = $('.gallery-images-list', field);
		list.scrollTop(list.height());
	}

	module.exports.init = function(trans, config) {
		app_data.trans = trans;
		app_data.config = config;
		
		$(document).ready(function() {
			init();
		});
	};


/***/ }
/******/ ]);