@extends('common::admin')

@section('head')
	<script type="text/javascript">
	/*var trans = {
		errors: {
			slug_empty: {!! json_encode(trans('common::admin.add.errors.slug-empty')) !!}, 
			slug_already_exists: {!! json_encode(trans('common::admin.add.errors.slug-already-exists')) !!}
		}, 
		messages: {
			deleted: {!! json_encode(trans('common::admin.add.messages.deleted')) !!}
		}
	};
	var config = {
		delete_route: {!! json_encode($delete_route === null ? null : route($delete_route)) !!}
	};
	
	requirejs([ 'app/modules/list' ], function(list) {
		list.init(trans, config);
	});*/
	$(document).ready(function() {
		$('.ui.accordion').accordion();
	});
	</script>
@stop

@section('content')
	<div class="ui icon input">
		<input type="text" placeholder="Search...">
		<i class="search icon"></i>
	</div>
	
	@foreach ($items as $type=>$type_items)
		<h3 class="ui block header">
			<i class="{{ $type == 'frontend' ? 'sitemap' : 'settings' }} icon"></i>
			{{ trans('translation::admin.list.type.' . $type) }}
		</h3>
		<div class="ui styled fluid accordion">
			@foreach ($type_items as $package=>$package_items)
				<div class="title">
					<i class="dropdown icon"></i>
					{{ trans($package . '::admin.title.main') }}
				</div>
				<div class="content">
					<table class="ui celled striped table">
						<thead><tr>
							<th>Edit</th>
							<th>Code</th>
							<th>En</th>
							<th>Sl</th>
						</tr></thead>
						<tbody>
							@foreach ($package_items as $name=>$id_translation_source)
								<tr class="{{ (false ? 'error' : (false ? 'warning' : '')) }}">
									<td class="collapsing">
										<a href="#" 
											class="ui label blue only-icon"><i class="write icon"></i></a>
									</td>
									<td>{!! strpos($name, '.') === false ? $name : 
										'<span style="color: rgba(40,40,40,.3)">' . 
											mb_substr($name, 0, mb_strrpos($name, '.')+1) . '</span>' . 
											mb_substr($name, mb_strrpos($name, '.')+1) !!}</td>
									<td class="center aligned collapsing">
										<i class="large {{ mt_rand() % 2 == 0 ? 'green checkmark' : 'red close' }} icon"></i>
									</td>
									<td class="center aligned collapsing">
										<i class="large {{ mt_rand() % 2 == 1 ? 'green checkmark' : 'red close' }} icon"></i>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@endforeach
		</div>
	@endforeach
@stop
