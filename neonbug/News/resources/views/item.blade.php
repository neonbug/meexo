<h1>{{ $item->title }}</h1>
<strong>{{ date('d.m.Y', strtotime($item->published_from_date)) }}</strong>

<div>
	{!! $item->contents !!}
</div>
