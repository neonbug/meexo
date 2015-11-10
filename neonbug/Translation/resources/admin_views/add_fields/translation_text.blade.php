<tr class="top aligned field-translation-text">
	<th class="collapsing">
		{{ $field_title }}
	</th>
	<td>
		<div class="field">
			<textarea name="field[{{ $id_language }}][{{ $field['name'] }}]" 
				data-name="{{ $field['name'] }}" data-type="translation_text">{{ $field['value'] }}</textarea>
		</div>
		<div class="field">
			<div class="ui checkbox">
				<input type="checkbox" />
				<label>{{ trans('translation::admin.add.edit-with-rich-editor') }}</label>
			</div>
		</div>
	</td>
</tr>
