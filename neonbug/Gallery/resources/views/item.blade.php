<style type="text/css">
.gallery-images
{
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
}
	.gallery-image
	{
		display: block;
		width: 180px;
		height: 100px;
		margin-right: 12px;
		margin-bottom: 12px;
		
		background-position: 0px 0px;
		transition: background-position 300ms, filter 300ms;
	}
	.gallery-image:hover
	{
		background-position: 0px -20px;
		filter: drop-shadow(0px 2px 4px #222222);
	}
</style>

<h1>{{ $item->title }}</h1>
<strong>{{ date('d.m.Y', strtotime($item->updated_at)) }}</strong>

<?php
$root_dir     = 'uploads';
$package_name = 'gallery';
$id_gallery   = $item->{$item->getKeyName()};
$id_language  = 0; //language independent field
$column_name  = 'images';

$images = Neonbug\Gallery\Models\GalleryImage::where('id_row', $item->{$item->getKeyName()})
	->where('column_name', $column_name)
	->orderBy('ord')
	->get();
?>

<div class="gallery-images">
	@foreach ($images as $image)
		<?php
		$image_path = implode('/', [ $root_dir, $package_name, $id_gallery, $id_language, $column_name, $image->image ]);
		
		$url   = Croppa::url($image_path);
		$thumb = Croppa::url($image_path, 180, 120);
		?>
		<a target="_blank" class="gallery-image" href="{{ $url }}" style="background-image: url('{{ $thumb }}');"></a>
	@endforeach
</div>
