<tr>
	<td class="collapsing">
		{{ $field_title }}
	</td>
	<td>
		<div class="field">
			<input type="file" name="field[{{ $id_language }}][{{ $field['name'] }}]" data-name="{{ $field['name'] }}" />
		</div>
	</td>
</tr>

@if ($field['value'] != null && $field['value'] != '')
	<tr>
		<td class="collapsing">
		</td>
		<td>
			<div class="field">
				<div class="ui card">
					<a class="image" href="{!! Croppa::url_resize('uploads/news/' . $field['value']) !!}" target="_blank">
						<img src="{!! Croppa::url_resize('uploads/news/' . $field['value'], 290) !!}" />
					</a>
					<div class="content">
						<div class="header">{{ trans('common::admin.add.current-image-title') }}</div>
						<div class="description">
							{{ trans('common::admin.add.current-image-description') }}
						</div>
					</div>
					<div class="extra content">
						<div class="ui checkbox">
							<input type="checkbox" name="field[{{ $id_language }}][remove-file][{{ $field['name'] }}]" 
								value="true" />
							<label>{{ trans('common::admin.add.current-image-remove') }}</label>
						</div>
					</div>
					<!-- <div class="ui bottom attached button">
						<i class="add icon"></i>
						Add Friend
					</div> -->
				</div>
			</div>
		</td>
	</tr>
@endif
