@extends('common::admin')

@section('head')
	<script type="text/javascript">
	var add = {
		current_ajax_requests: {}, 
		
		init: function() {
			add.initSlugs();
			add.initSaveButton();
			add.initMessageClose();
		}, 
		initSlugs: function() {
			$('[data-type="slug"]').each(function(idx, item) {
				var generate_from = $('[data-name="' + item.dataset.slugGenerateFrom + '"]', $(item).closest('.tab'));
				generate_from = (generate_from.length == 0 ? null : $(generate_from.get(0)));
				if (generate_from.length == 0) return;
				
				$(item).change(function() {
					this.dataset.slugIsEmpty = (this.value.length == 0 ? 'true' : 'false');
					
					if (generate_from == null) return;
					
					if (this.dataset.slugIsEmpty == 'true')
					{
						add.updateSlug($(item), $(generate_from));
					}
					add.checkSlug($(item));
				});
				
				if (generate_from != null)
				{
					generate_from.keyup(function() {
						add.updateSlug($(item), generate_from);
						add.checkSlug($(item));
					});
					add.updateSlug($(item), generate_from);
				}
			});
		}, 
		updateSlug: function(slug_field, generate_from_field) {
			if (slug_field.get(0).dataset.slugIsEmpty == 'false') return;
			slug_field.val(getSlug(generate_from_field.val()));
		}, 
		checkSlug: function(slug_field) {
			var value = slug_field.val();
			var name = slug_field.attr('name');
			
			if (add.current_ajax_requests[name] != undefined)
			{
				add.current_ajax_requests[name].abort();
				add.current_ajax_requests[name] = undefined;
			}
			
			var field = $('.field[data-name="' + slug_field.attr('name') + '"]');
			var error_label = $('.error-label', field);
			
			if (value.length == 0)
			{
				error_label.html({!! json_encode(trans('common::admin.add.errors.slug-empty')) !!});
				add.markSlugField(slug_field, true);
			}
			else
			{
				var icon_div = $('.field[data-name="' + name + '"] .ui.icon.input');
				icon_div.addClass('loading');
				field.addClass('loading');
				
				var post_data = {
					value: value, 
					id_language: slug_field.data('id-language'), 
					id_item: {{ $item == null ? -1 : $item->{$item->getKeyName()} }}
				};
				
				add.current_ajax_requests[name] = $.post({!! json_encode(route($check_slug_route)) !!}, post_data, 
					function(data) {
					add.current_ajax_requests[name] = undefined;
					
					//TODO check for generic errors, like TokenMismatch (should catch it in Error handler of ajax response); do sth smart in that case .. like .. reload?
					
					error_label.html({!! json_encode(trans('common::admin.add.errors.slug-already-exists')) !!});
					add.markSlugField(slug_field, !data.valid);
				}, 'json');
			}
		}, 
		markSlugField: function(slug_field, is_error) {
			var field = $('.field[data-name="' + slug_field.attr('name') + '"]');
			var icon_div = $('.field[data-name="' + slug_field.attr('name') + '"] .ui.icon.input');
			var icon = $('.field[data-name="' + slug_field.attr('name') + '"] .ui.icon.input .icon');
			
			icon_div.removeClass('loading');
			field.removeClass('loading');
			if (is_error)
			{
				field.addClass('error');
				icon.removeClass('checkmark').addClass('remove');
			}
			else
			{
				field.removeClass('error');
				icon.removeClass('remove').addClass('checkmark');
			}
		}, 
		
		initSaveButton: function() {
			$('form.add').submit(function(e) {
				//if we're still loading stuff, don't continue
				if ($('.field.loading').length > 0)
				{
					e.preventDefault();
					//TODO inform user why nothing is happening
					return;
				}
				
				//if there are errors on the form, tell that to the user, and don't continue
				if ($('.field.error').length > 0)
				{
					e.preventDefault();
					
					$('.errors-modal').modal({
						blurring: true
					}).modal('show');
					
					return;
				}
				
				if ($('.preview-button').hasClass('loading'))
				{
					$('.preview-button').removeClass('loading');
				}
				else
				{
					$('.save-button').addClass('loading').attr('disabled', 'disabled');
				}
			});
			
			$('.preview-button').click(function() {
				$('.preview-button').addClass('loading');
			});
		}, 
		initMessageClose: function() {
			$('.message .close').on('click', function() {
				$(this).parent().transition('fade down');
			});
		}
	};
	
	$(document).ready(function() {
		add.init();
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
					@include('common::admin.add_fields.' . $field['type'], [ 'id_language' => -1, 'field' => $field, 
						'field_title' => trans($package_name . '::admin.add.field-title.' . $field['name']), 
						'prefix' => $prefix ])
				@endforeach
			</tbody></table>
		</div>
		@foreach ($languages as $language)
			<?php if (!array_key_exists($language->id_language, $fields['language_dependent'])) continue; ?>
			<div class="ui bottom attached tab segment" data-tab="{{ $language->locale }}">
				<table class="ui very basic table"><tbody>
					@foreach ($fields['language_dependent'][$language->id_language] as $field)
						@include('common::admin.add_fields.' . $field['type'], 
							[ 'id_language' => $language->id_language, 'field' => $field, 
								'field_title' => trans($package_name . '::admin.add.field-title.' . $field['name']), 
								'prefix' => $prefix ])
					@endforeach
				</tbody></table>
			</div>
		@endforeach
		
		<div class="ui hidden divider"></div>
		
		<button type="submit" class="save-button ui button orange">
			<i class="icon checkmark"></i>
			{{ trans('common::admin.add.save-button') }}
		</button>
		<button type="submit" formaction="?preview" formtarget="_blank" class="preview-button ui button">
			{{ trans('common::admin.add.preview-button') }}
		</button>
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
