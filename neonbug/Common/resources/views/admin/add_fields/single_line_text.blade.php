<tr class="top aligned field-single-line-text">
	<th class="collapsing">
		{{ $field_title }}
	</th>
	<td>
		<div class="field">
			<input type="text" name="field[{{ $id_language }}][{{ $field['name'] }}]" value="{{ $field['value'] }}"
				data-name="{{ $field['name'] }}" 
				placeholder="{{ array_key_exists('placeholder', $field) ? trans($field['placeholder']) : '' }}" />
		</div>
	</td>
</tr>
