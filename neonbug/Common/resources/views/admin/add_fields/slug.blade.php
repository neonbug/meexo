<tr>
	<td class="collapsing">
		{{ $field_title }}
	</td>
	<td>
		<div class="field">
			<input type="text" name="field[{{ $id_language }}][{{ $field['name'] }}]" value="{{ $field['value'] }}" 
				data-name="{{ $field['name'] }}" data-type="slug" data-slug-generate-from="{{ $field['generate_from'] }}"
				data-slug-is-empty="{{ mb_strlen($field['value']) == 0 ? 'true' : 'false' }}" />
		</div>
	</td>
</tr>
