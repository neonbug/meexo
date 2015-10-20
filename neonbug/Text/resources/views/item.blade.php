<h1>{{ $item->{$item->getKeyName()} }}</h1>
<strong>{{ date('d.m.Y', strtotime($item->updated_at)) }}</strong>

<div>
	{!! $item->contents !!}
</div>