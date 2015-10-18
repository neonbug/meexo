module.exports = {};

var app_data = {};

function initRichEditors()
{
	var editor_instances = {};
	var is_mobile = ($(window).width() < 768);
	var remove_buttons = is_mobile ? 
		'Print,Preview,Save,Templates,NewPage,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Language,BidiRtl,BidiLtr,About,Source,Cut,Copy,Paste,PasteText,PasteFromWord,Outdent,Indent,Blockquote,CreateDiv,Flash,SpecialChar,Smiley,PageBreak,Iframe,ShowBlocks'
		:
		'Print,Preview,Save,Templates,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Language,BidiRtl,BidiLtr,About,Flash,NewPage';
	
	$('.field-translation-text').each(function(idx, el) {
		$('[type="checkbox"]', el).change(function() {
			var textarea = $('textarea', el);
			if (this.checked)
			{
				editor_instances[textarea.attr('name')] = CKEDITOR.replace(textarea.get(0), {
					entities: false, 
					basicEntities: false, 
					baseHref: config.base_url, 
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
			}
			else
			{
				if (editor_instances[textarea.attr('name')] != undefined)
				{
					editor_instances[textarea.attr('name')].destroy();
				}
			}
		});
	});
}

module.exports.init = function(trans, config) {
	app_data.trans = trans;
	app_data.config = config;
	
	$(document).ready(function() {
		initRichEditors();
	});
};
