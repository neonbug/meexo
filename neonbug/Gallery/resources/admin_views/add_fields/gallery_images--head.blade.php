<link rel="stylesheet" type="text/css" href="{{ cached_asset('vendor/gallery/admin_assets/css/gallery_images.css') }}" />

<script src="{{ cached_asset('vendor/gallery/admin_assets/js/app/gallery_images.js') }}"></script>
<script type="text/javascript">
var trans = {
	errors: {
		upload_error: {!! json_encode(trans('gallery::admin.add.field-gallery-images.upload-error')) !!}, 
		upload_error_close: {!! json_encode(trans('gallery::admin.add.field-gallery-images.upload-error-close')) !!}
	}
};

var config = {
	upload_gallery_file_route: {!! json_encode(route('gallery::admin::upload-gallery-file', [ 'UPLOAD_DIR' ])) !!}, 
	temp_small_image_template_url: {!! json_encode(Croppa::url('uploads/gallery/temp/{UPLOAD_DIR}/{FILENAME}.{EXT}', 
		180, 120)) !!}, 
	temp_image_template_url: {!! json_encode(Croppa::url('uploads/gallery/temp/{UPLOAD_DIR}/{FILENAME}.{EXT}')) !!}
};

gallery_images.init(trans, config);
</script>

<script type="text/template" id="gallery-images-image-template">
	@include('gallery_admin::add_fields.gallery_images-image', [ 'item' => null, 'image' => null, 'field' => null, 
		'id_language' => null ])
</script>
