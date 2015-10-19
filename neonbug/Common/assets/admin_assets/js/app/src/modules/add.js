var global      = require('./global');
var speakingurl = require('speakingurl');
var moment      = require('moment');
var pikaday     = require('pikaday');

module.exports = {};

var app_data = {};

var current_ajax_requests = {};

function initSlugs() {
	$('[data-type="slug"]').each(function(idx, item) {
		var generate_from = $('[data-name="' + item.dataset.slugGenerateFrom + '"]', $(item).closest('.tab'));
		generate_from = (generate_from.length == 0 ? null : $(generate_from.get(0)));
		if (generate_from.length == 0) return;
		
		$(item).change(function() {
			this.dataset.slugIsEmpty = (this.value.length == 0 ? 'true' : 'false');
			
			if (generate_from == null) return;
			
			if (this.dataset.slugIsEmpty == 'true')
			{
				updateSlug($(item), $(generate_from));
			}
			checkSlug($(item));
		});
		
		if (generate_from != null)
		{
			generate_from.keyup(function() {
				updateSlug($(item), generate_from);
				
				//TODO maybe delay calling checkSlug?
				checkSlug($(item));
			});
			updateSlug($(item), generate_from);
		}
	});
}

function updateSlug(slug_field, generate_from_field) {
	if (slug_field.get(0).dataset.slugIsEmpty == 'false') return;
	slug_field.val(speakingurl(generate_from_field.val()));
}

function checkSlug(slug_field) {
	var value = slug_field.val();
	var name = slug_field.attr('name');
	
	if (current_ajax_requests[name] != undefined)
	{
		current_ajax_requests[name].abort();
		current_ajax_requests[name] = undefined;
	}
	
	var field = $('.field[data-name="' + slug_field.attr('name') + '"]');
	var error_label = $('.error-label', field);
	
	if (value.length == 0)
	{
		error_label.html(app_data.trans.errors.slug_empty);
		markSlugField(slug_field, true);
	}
	else
	{
		var icon_div = $('.field[data-name="' + name + '"] .ui.icon.input');
		icon_div.addClass('loading');
		field.addClass('loading');
		
		var post_data = {
			value: value, 
			id_language: slug_field.data('id-language'), 
			id_item: app_data.config.id_item
		};
		
		current_ajax_requests[name] = $.post(app_data.config.check_slug_route, post_data, 
			function(data) {
			current_ajax_requests[name] = undefined;
			
			//TODO check for generic errors, like TokenMismatch (should catch it in Error handler of ajax response); do sth smart in that case .. like .. reload?
			
			error_label.html(app_data.trans.errors.slug_already_exists);
			markSlugField(slug_field, !data.valid);
		}, 'json');
	}
}

function markSlugField(slug_field, is_error) {
	var field = $('.field[data-name="' + slug_field.attr('name') + '"]');
	var icon_div = $('.field[data-name="' + slug_field.attr('name') + '"] .ui.icon.input');
	var icon = $('.field[data-name="' + slug_field.attr('name') + '"] .ui.icon.input .icon');
	
	icon_div.removeClass('loading');
	field.removeClass('loading');
	if (is_error)
	{
		field.addClass('error');
		icon.removeClass('checkmark').addClass('remove');
	}
	else
	{
		field.removeClass('error');
		icon.removeClass('remove').addClass('checkmark');
	}
}

function initSaveButton() {
	$('form.add').submit(function(e) {
		//if we're still loading stuff, don't continue
		if ($('.field.loading').length > 0)
		{
			e.preventDefault();
			//TODO inform user why nothing is happening
			return;
		}
		
		//if there are errors on the form, tell that to the user, and don't continue
		if ($('.field.error').length > 0)
		{
			e.preventDefault();
			
			$('.errors-modal').modal({
				blurring: true
			}).modal('show');
			
			return;
		}
		
		if ($('.preview-button').hasClass('loading'))
		{
			$('.preview-button').removeClass('loading');
		}
		else
		{
			$('.save-button').addClass('loading').attr('disabled', 'disabled');
		}
	});
	
	$('.preview-button').click(function() {
		$('.preview-button').addClass('loading');
	});
}

function initMessageClose() {
	$('.message .close').on('click', function() {
		$(this).parent().transition('fade down');
	});
}

function initRichEditors() {
	var is_mobile = ($(window).width() < 768);
	var remove_buttons = is_mobile ? 
		'Print,Preview,Save,Templates,NewPage,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Language,BidiRtl,BidiLtr,About,Source,Cut,Copy,Paste,PasteText,PasteFromWord,Outdent,Indent,Blockquote,CreateDiv,Flash,SpecialChar,Smiley,PageBreak,Iframe,ShowBlocks'
		:
		'Print,Preview,Save,Templates,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Language,BidiRtl,BidiLtr,About,Flash,NewPage';
	
	$('textarea[data-type="rich_text"]').each(function(idx, el) {
		CKEDITOR.replace(el, {
			entities: false, 
			baseHref: app_data.config.base_url, 
			toolbarGroups: [
				{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
				{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
				{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
				{ name: 'forms', groups: [ 'forms' ] },
				{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
				{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
				'/',
				{ name: 'links', groups: [ 'links' ] },
				{ name: 'insert', groups: [ 'insert' ] },
				{ name: 'styles', groups: [ 'styles' ] },
				{ name: 'colors', groups: [ 'colors' ] },
				{ name: 'tools', groups: [ 'tools' ] },
				{ name: 'others', groups: [ 'others' ] },
				{ name: 'about', groups: [ 'about' ] }
			], 
			removeButtons: remove_buttons
		});
	});
}

function initCheckboxes() {
	$('.ui.checkbox').checkbox({
		onChange: function() {
			$('[name="' + this.dataset.name + '"]').val(this.checked ? 'true' : 'false');
		}
	});
}

function initTabs() {
	$('.menu .item').tab();
}

function initDatePicker() {
	moment.locale(window.navigator.language);
	
	$('[data-type="date"]').each(function(index, item) {
		var picker = new pikaday({
			field: item,
			firstDay: 1,
			format: app_data.config.formatter_date_pattern, 
			onSelect: function(date) {
				$('[name="' + item.dataset.dateRel + '"]').val(moment(date).format('YYYY-MM-DD'));
			}
		});
	});
}

function initMessages() {
	app_data.config.messages.forEach(function(message) {
		global.showToast('success', message);
	});
}

function initErrorMessages() {
	app_data.config.errors.forEach(function(message) {
		global.showToast('error', message);
	});
}

function initDropdowns() {
	$('.ui.dropdown').dropdown();
}

module.exports.init = function(trans, config) {
	app_data.trans = trans;
	app_data.config = config;
	
	$(document).ready(function() {
		initSlugs();
		initSaveButton();
		initMessageClose();
		initRichEditors();
		initCheckboxes();
		initTabs();
		initDatePicker();
		initMessages();
		initErrorMessages();
		initDropdowns();
	});
};
