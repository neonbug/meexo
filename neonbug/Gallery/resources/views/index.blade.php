<h1>Gallery</h1>

@foreach ($items as $item)
	<?php if (mb_strlen($item->slug) == 0) continue; ?>
	
	<div>
		<h2><a href="{{ route('gallery::slug::' . $item->slug) }}">{{ $item->{$item->getKeyName()} }}</a></h2>
		<strong>{{ date('d.m.Y', strtotime($item->updated_at)) }}</strong>
	</div>
@endforeach
