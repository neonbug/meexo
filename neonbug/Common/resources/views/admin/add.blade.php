@extends('common::admin')

@section('head')
	<script src="{{ cached_asset('vendor/common/admin_assets/js/app/add.js') }}"></script>
	<script type="text/javascript">
	var trans = {
		errors: {
			slug_empty: {!! json_encode(trans('common::admin.add.errors.slug-empty')) !!}, 
			slug_already_exists: {!! json_encode(trans('common::admin.add.errors.slug-already-exists')) !!}
		}
	};
	
	var config = {
		id_item: {{ $item == null ? -1 : $item->{$item->getKeyName()} }}, 
		check_slug_route: {!! json_encode(route($check_slug_route)) !!}, 
		formatter_date_pattern: {!! json_encode($formatter->getShortDatePattern()) !!}, 
		messages: {!! json_encode(isSet($messages) ? $messages : []) !!}, 
		errors: {!! json_encode($errors->all()) !!}, 
		base_url: {!! json_encode(url()) !!}
	};
	
	add.init(trans, config);
	</script>
	
	<?php
	$unique_types = [];
	?>
	@foreach ($fields['language_independent'] as $field)
		<?php
		$type = (stripos($field['type'], '::') !== false ? $field['type'] : 
			'common::admin.add_fields.' . $field['type']);
		if (!in_array($type, $unique_types)) $unique_types[] = $type;
		?>
	@endforeach
	@foreach ($languages as $language)
		<?php if (!array_key_exists($language->id_language, $fields['language_dependent'])) continue; ?>
		@foreach ($fields['language_dependent'][$language->id_language] as $field)
			<?php
			$type = (stripos($field['type'], '::') !== false ? $field['type'] : 
				'common::admin.add_fields.' . $field['type']);
			if (!in_array($type, $unique_types)) $unique_types[] = $type;
			?>
		@endforeach
	@endforeach
	
	@foreach ($unique_types as $type)
		@if (view()->exists($type . '--head'))
			@include($type . '--head')
		@endif
	@endforeach
@stop

@section('content')
	<form class="ui form add" method="POST" enctype="multipart/form-data" autocomplete="off">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		
		<div class="ui top attached tabular menu">
			<a class="active item" data-tab="general">{{ trans('common::admin.add.tab-title-general') }}</a>
			@foreach ($languages as $language)
				<?php if (!array_key_exists($language->id_language, $fields['language_dependent'])) continue; ?>
				<a class="item" data-tab="{{ $language->locale }}">{{ $language->name }}</a>
			@endforeach
		</div>
		<div class="ui bottom attached active tab segment" data-tab="general">
			<table class="ui very basic table"><tbody>
				@foreach ($fields['language_independent'] as $field)
					<?php
					$type = (stripos($field['type'], '::') !== false ? $field['type'] : 
						'common::admin.add_fields.' . $field['type']);
					$params = [ 
						'item' => $item, 
						'id_language' => -1, 
						'field' => $field, 
						'field_title' => trans($package_name . '::admin.add.field-title.' . $field['name']), 
						'prefix' => $prefix
					];
					?>
					@include($type, $params)
				@endforeach
			</tbody></table>
		</div>
		@foreach ($languages as $language)
			<?php if (!array_key_exists($language->id_language, $fields['language_dependent'])) continue; ?>
			<div class="ui bottom attached tab segment" data-tab="{{ $language->locale }}">
				<table class="ui very basic table"><tbody>
					@foreach ($fields['language_dependent'][$language->id_language] as $field)
						<?php
						$type = (stripos($field['type'], '::') !== false ? $field['type'] : 
							'common::admin.add_fields.' . $field['type']);
						$params = [ 
							'item' => $item, 
							'id_language' => $language->id_language, 
							'field' => $field, 
							'field_title' => trans($package_name . '::admin.add.field-title.' . $field['name']), 
							'prefix' => $prefix
						];
						?>
						@include($type, $params)
					@endforeach
				</tbody></table>
			</div>
		@endforeach
		
		<div class="ui hidden divider"></div>
		
		<button type="submit" class="save-button ui button orange">
			<i class="icon checkmark"></i>
			{{ trans('common::admin.add.save-button') }}
		</button>
		@if (!isSet($supports_preview) || $supports_preview === true)
			<button type="submit" formaction="?preview" formtarget="_blank" class="preview-button ui button">
				{{ trans('common::admin.add.preview-button') }}
			</button>
		@endif
	</form>
	<div class="ui small modal errors-modal">
		<div class="content">
			{{ trans('common::admin.add.error-dialog-message') }}
		</div>
		<div class="actions">
			<div class="ui ok right labeled icon button orange">
				{{ trans('common::admin.add.error-dialog-confirm') }}
				<i class="checkmark icon"></i>
			</div>
		</div>
	</div>
@stop
