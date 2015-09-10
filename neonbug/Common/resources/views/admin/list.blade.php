@extends('common::admin')

@section('head')
	<script type="text/javascript">
	var trans = {};
	var config = {
		delete_route: {!! json_encode($delete_route === null ? null : route($delete_route)) !!}
	};
	
	requirejs([ 'app/modules/list' ], function(list) {
		list.init(trans, config);
	});
	</script>
@stop

@section('content')
	<table class="ui striped padded table">
		<thead>
			<tr>
				@if ($edit_route != null)
					<th>{{ trans('common::admin.list.edit-action') }}</th>
				@endif
				@if ($delete_route != null)
					<th>{{ trans('common::admin.list.delete-action') }}</th>
				@endif
				@foreach ($fields as $field_name=>$field)
					<th>{{ trans($package_name . '::admin.list.field-title.' . $field_name) }}</th>
				@endforeach
			</tr>
		</thead>
		<tbody>
			@foreach ($items as $item)
				<tr>
					@if ($edit_route != null)
						<td class="collapsing">
							<a href="{{ route($edit_route, [ $item->{$item->getKeyName()} ]) }}" 
								class="ui label blue only-icon"><i class="write icon"></i></a>
						</td>
					@endif
					@if ($delete_route != null)
						<td class="collapsing">
							<a href="#" class="ui label red only-icon delete-item" 
								data-id-item="{{ $item->{$item->getKeyName()} }}"><i class="trash icon"></i></a>
						</td>
					@endif
					@foreach ($fields as $field_name=>$field)
						<td>
							@include('common::admin.list_fields.' . $field['type'], 
								[ 'item' => $item, 'field_name' => $field_name, 'field' => $field ])
						</td>
					@endforeach
				</tr>
			@endforeach
		</tbody>
	</table>
	<div class="ui small modal delete-item-modal">
		<div class="content">
			{{ trans('common::admin.list.delete-confirmation-message') }}
		</div>
		<div class="actions">
			<div class="ui black deny button">
				{{ trans('common::admin.list.delete-confirmation-deny') }}
			</div>
			<div class="ui ok right labeled icon button red">
				{{ trans('common::admin.list.delete-confirmation-confirm') }}
				<i class="checkmark icon"></i>
			</div>
		</div>
	</div>
@stop
