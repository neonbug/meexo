<tr class="top aligned field-multi-line-text">
	<th class="collapsing">
		{{ $field_title }}
	</th>
	<td>
		<div class="field">
			<textarea name="field[{{ $id_language }}][{{ $field['name'] }}]" 
				data-name="{{ $field['name'] }}" data-type="multi_line_text">{{ $field['value'] }}</textarea>
		</div>
	</td>
</tr>
