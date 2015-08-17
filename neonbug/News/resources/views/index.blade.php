<h1>News</h1>

@foreach ($items as $item)
	<div>
		<h2><a href="{{ route('news::slug::' . $item->slug) }}">{{ $item->title }}</a></h2>
		<strong>{{ date('d.m.Y', strtotime($item->published_from_date)) }}</strong>
		<div>
			{!! $item->excerpt !!}
		</div>
	</div>
@endforeach
