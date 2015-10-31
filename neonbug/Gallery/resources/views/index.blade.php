<h1>Gallery</h1>

@foreach ($items as $item)
	<?php if (mb_strlen($item->slug) == 0) continue; ?>
	
	<a href="{{ route('gallery::slug::' . $item->slug) }}" style="display: block;">
		<div style="border: 1px solid black; width: 150px; height: 150px; float: left; margin-right: 12px;">
			@if ($item->main_image != null && $item->main_image != '')
				<img src="{!! Croppa::url('uploads/gallery/' . $item->main_image, 150, 150) !!}" />
			@endif
		</div>
		
		<h2>{{ $item->title }}</h2>
		<strong>{{ date('d.m.Y', strtotime($item->updated_at)) }}</strong>
		
		<div style="clear: both;"></div>
	</a>
@endforeach
