<style type="text/css">
.add .tab.segment .ui.table tr.field-gallery-images th
{
	padding-top: 30px;
}
.gallery-images-upload-container .gallery-images-browse, 
	.gallery-images-upload-container .gallery-images-drop-target
{
	float: left;
	margin-right: 24px;
}
.gallery-images-upload-container .gallery-images-drop-target
{
	line-height: 14px;
	
	padding-top: 9px;
	padding-right: 19px;
	padding-bottom: 9px;
	padding-left: 19px;
	
	border: 2px dashed #dddddd;
	color: #dddddd;
	border-radius: 0.285714rem;
}
.gallery-images-upload-container .gallery-images-drop-target.drag-over
{
	background-color: #f2711c;
	border: 2px dashed #ffffff;
	color: #ffffff;
}
	.gallery-images-upload-container .gallery-images-drop-target *
	{
		/* prevent multiple dragenter/dragleave events */
		pointer-events: none;
	}
.ui.segment.gallery-images-list-container
{
	padding-left: 0px;
}
	.gallery-images-list
	{
		max-height: 600px;
		overflow: auto;
	}
		.ui.card.gallery-images-image
		{
			float: left;
			width: 180px;
			
			margin-right: 12px;
			margin-bottom: 12px;
		}
			.ui.card.gallery-images-image > .image
			{
				display: block;
				position: relative;
				width: 180px;
				height: 120px;
				
				background-repeat: no-repeat;
				background-position: center center;
			}
				.gallery-images-image-upload-overlay-progress
				{
					position: absolute;
					left: 0px;
					right: 0px;
					bottom: 0px;
					
					height: 0%;
					
					background-color: #f2711c;
				}
				.gallery-images-image-upload-overlay
				{
					position: absolute;
					left: 0px;
					right: 0px;
					top: 0px;
					bottom: 0px;
					
					color: #000000;
				}
					.gallery-images-image-progress
					{
						display: block;
						text-align: center;
						line-height: 120px;
					}
			.ui.card.gallery-images-image .description
			{
				overflow: hidden;
				text-overflow: ellipsis;
				white-space: nowrap;
			}
</style>

<script type="text/javascript">
var gallery_images = {
	init: function() {
		$('.gallery-images-list').sortable({ 
			handle: 'image', 
			forcePlaceholderSize: true, 
			placeholder: '<div class="ui card gallery-images-image"></div>'
		});
	}, 
	reloadSortable: function() {
		$('.gallery-images-list').sortable();
	}, 
	scrollToBottom: function(field) {
		var list = $('.gallery-images-list', field);
		list.scrollTop(list.height());
	}
};

$(document).ready(function() {
	gallery_images.init();
	
	$('.field-gallery-images .field').each(function(idx, el) {
		var token = $('meta[name="csrf_token"]').attr('content');
		
		var upload_dir = Math.floor(Math.random() * (1000000 - 1)) + 1;
		
		var flow = new Flow({
			target: {!! json_encode(route('gallery::admin::upload-gallery-file', [ 'UPLOAD_DIR' ])) !!}
				.replace('UPLOAD_DIR', upload_dir), 
			headers: { 'X-XSRF-TOKEN': token }
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
			
			gallery_images.reloadSortable();
			gallery_images.scrollToBottom(el);
		});
		
		flow.on('fileProgress', function(file) {
			var item = $('.gallery-images-image[data-id="' + file.uniqueIdentifier + '"]', el);
			
			var percent_value = Math.floor(file.progress()*100);
			
			$('.gallery-images-image-progress', item).text(percent_value + ' %');
			$('.gallery-images-image-upload-overlay-progress', item).css('height', percent_value + '%');
		});
		
		flow.on('fileSuccess', function(file) {
			var item = $('.gallery-images-image[data-id="' + file.uniqueIdentifier + '"]', el);
			
			var image_small_url = {!! json_encode(Croppa::url('uploads/gallery/temp/{UPLOAD_DIR}/{FILENAME}.{EXT}', 
				180, 120)) !!};
			var image_url = {!! json_encode(Croppa::url('uploads/gallery/temp/{UPLOAD_DIR}/{FILENAME}.{EXT}')) !!};
			
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
				'<div class="content">Error during upload</div>' + 
				'<div class="actions"><div class="ui cancel button red">Close</div></div>' + 
			'</div>').modal('show');
		});
	});
	
	$('.gallery-images-image-remove').on('click', function() {
		$(this).parents('.gallery-images-image').remove();
	});
});
</script>
<script type="text/template" id="gallery-images-image-template">
	@include('gallery::admin.add_fields.gallery_images-image', [ 'item' => null, 'image' => null, 'field' => null, 
		'id_language' => null ])
</script>
