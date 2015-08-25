@extends('common::admin')

@section('head')
	<script type="text/javascript">
	$(document).ready(function() {
		@if ($delete_route != null)
			$('.delete-item').click(function() {
				var id_item = this.dataset.idItem;
				var modal = $('.delete-item-modal');
				
				modal.modal({
					blurring: true, 
					onApprove: function() {
						$('.ui.ok', modal).addClass('loading');
						
						$.post({!! json_encode(route($delete_route)) !!}, { id: id_item }, function(data) {
							//TODO check data.success
							//TODO also check for generic errors, like TokenMismatch (should catch it in Error handler of ajax response); do sth smart in that case .. like .. reload?
							
							modal.modal('hide');
							$('.ui.ok', modal).removeClass('loading');
							
							document.location.reload();
						}, 'json');
						
						return false;
					}
				}).modal('show');
			});
		@endif
	});
	</script>
@stop

@section('content')
	<table class="ui striped padded table">
		<thead>
			<tr>
				@if ($edit_route != null)
					<th>{{ trans('admin.common.list.edit-action') }}</th>
				@endif
				@if ($delete_route != null)
					<th>{{ trans('admin.common.list.delete-action') }}</th>
				@endif
				@foreach ($fields as $field_name=>$field)
					<th>{{ $field_name }}</th>
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
			{{ trans('admin.common.list.delete-confirmation-message') }}
		</div>
		<div class="actions">
			<div class="ui black deny button">
				{{ trans('admin.common.list.delete-confirmation-deny') }}
			</div>
			<div class="ui ok right labeled icon button red">
				{{ trans('admin.common.list.delete-confirmation-confirm') }}
				<i class="checkmark icon"></i>
			</div>
		</div>
	</div>
@stop
