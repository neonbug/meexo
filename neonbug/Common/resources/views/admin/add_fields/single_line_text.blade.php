<tr class="top aligned field-single-line-text">
	<th class="collapsing">
		{{ $field_title }}
	</th>
	<td>
		<div class="field">
			<input type="text" name="field[{{ $id_language }}][{{ $field['name'] }}]" value="{{ $field['value'] }}"
				data-name="{{ $field['name'] }}" />
		</div>
	</td>
</tr>
