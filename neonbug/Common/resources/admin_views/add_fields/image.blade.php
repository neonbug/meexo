<tr class="top aligned field-image">
	<th class="collapsing">
		{{ $field_title }}
	</th>
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
					<div class="content">
						<div class="header">{{ trans('common::admin.add.current-image-title') }}</div>
					</div>
					<a class="image" href="{!! Croppa::url_resize('uploads/' . $prefix . '/' . $field['value']) !!}" 
						target="_blank">
						<img src="{!! Croppa::url_resize('uploads/' . $prefix . '/' . $field['value'], 290) !!}" />
					</a>
					<div class="content">
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
				</div>
			</div>
		</td>
	</tr>
@endif
