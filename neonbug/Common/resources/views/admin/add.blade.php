@extends('common::admin')

@section('head')
	<script type="text/javascript">
	$(document).ready(function() {
		$('.save-button').click(function() {
			$('.save-button').addClass('loading').attr('disabled', 'disabled');
		});
		
		$('.message .close').on('click', function() {
			$(this).parent().transition('fade down');
		});
	});
	</script>
@stop

@section('content')
	@if (isSet($messages) && count($messages) > 0)
		<div class="ui info icon message">
			<i class="close icon"></i>
			<i class="smile icon"></i>
			<div class="content">
				@foreach ($messages as $message)
					<p>{{ $message }}</p>
				@endforeach
			</div>
		</div>
	@endif
	@if (count($errors) > 0)
		<div class="ui error icon message">
			<i class="close icon"></i>
			<i class="frown icon"></i>
			<div class="content">
				@foreach ($errors->all() as $error)
					<p>{{ $error }}</p>
				@endforeach
			</div>
		</div>
	@endif
	
	<form class="ui form add" method="POST" autocomplete="off">
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
					<tr>
						@include('common::admin.add_fields.' . $field['type'], [ 'id_language' => -1, 'field' => $field ])
					</tr>
				@endforeach
			</tbody></table>
		</div>
		@foreach ($languages as $language)
			<?php if (!array_key_exists($language->id_language, $fields['language_dependent'])) continue; ?>
			<div class="ui bottom attached tab segment" data-tab="{{ $language->locale }}">
				<table class="ui very basic table"><tbody>
					@foreach ($fields['language_dependent'][$language->id_language] as $field)
						<tr>
							@include('common::admin.add_fields.' . $field['type'], 
								[ 'id_language' => $language->id_language, 'field' => $field ])
						</tr>
					@endforeach
				</tbody></table>
			</div>
		@endforeach
		
		<div class="ui hidden divider"></div>
		
		<button type="submit" class="save-button ui button orange">
			{{ trans('common::admin.add.save-button') }}
		</button>
		<button type="submit" formaction="?preview" formtarget="_blank" class="preview-button ui button">
			{{ trans('common::admin.add.preview-button') }}
		</button>
	</form>
@stop
