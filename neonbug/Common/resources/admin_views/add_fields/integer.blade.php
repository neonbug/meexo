<tr class="top aligned field-integer">
	<th class="collapsing">
		{{ $field_title }}
	</th>
	<td>
		<div class="field">
			<input type="number" min="0" step="1" name="field[{{ $id_language }}][{{ $field['name'] }}]" 
				value="{{ $field['value'] }}" data-name="{{ $field['name'] }}" />
		</div>
	</td>
</tr>
