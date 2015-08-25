<div class="ui inverted vertical menu sticky fluid borderless">
	<?php
	$route = route('admin-home');
	$active = ($route == Request::url());
	?>
	<a class="item {{ ($active ? 'active' : '') }}" href="{{ $route }}">
		<i class="home icon"></i>
		{{ trans('admin.common.menu.dashboard') }}
	</a>
	
	@foreach ($menu_items as $group_item)
		<div class="header item">
			<i class="newspaper icon"></i>
			{{ $group_item['title'] }}
		</div>
		@foreach ($group_item['items'] as $menu_item)
			<?php
			$route = route($menu_item['route']);
			$active = ($route == Request::url());
			?>
			
			<a class="item {{ ($active ? 'active' : '') }} level-two" href="{{ $route }}">
				{{ $menu_item['title'] }}
			</a>
		@endforeach
	@endforeach
</div>
