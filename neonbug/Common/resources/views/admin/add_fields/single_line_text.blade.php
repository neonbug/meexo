<tr>
	<td class="collapsing">
		{{ $field_title }}
	</td>
	<td>
		<div class="field">
			<input type="text" name="field[{{ $id_language }}][{{ $field['name'] }}]" value="{{ $field['value'] }}"
				data-name="{{ $field['name'] }}" />
		</div>
	</td>
</tr>
