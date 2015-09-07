<h1>News</h1>

@foreach ($items as $item)
	<a href="{{ route('news::slug::' . $item->slug) }}" style="display: block;">
		<div style="border: 1px solid black; width: 150px; height: 150px; float: left; margin-right: 12px;">
			@if ($item->main_image != null && $item->main_image != '')
				<img src="{!! Croppa::url('uploads/news/' . $item->main_image, 150, 150) !!}" />
			@endif
		</div>
		
		<h2>{{ $item->title }}</h2>
		<strong>{{ date('d.m.Y', strtotime($item->published_from_date)) }}</strong>
		<div>
			{!! $item->excerpt !!}
		</div>
		
		<div style="clear: both;"></div>
	</a>
@endforeach
