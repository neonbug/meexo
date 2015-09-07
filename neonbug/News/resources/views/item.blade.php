<h1>{{ $item->title }}</h1>
<strong>{{ date('d.m.Y', strtotime($item->published_from_date)) }}</strong>

@if ($item->main_image != null && $item->main_image != '')
<div>
	<img src="{!! Croppa::url('uploads/news/' . $item->main_image, 150, 150) !!}" />
</div>
@endif

<div>
	{!! $item->contents !!}
</div>
