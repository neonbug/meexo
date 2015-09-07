<tr class="top aligned field-slug">
	<th class="collapsing">
		{{ $field_title }}
	</th>
	<td>
		<div class="field" data-name="field[{{ $id_language }}][{{ $field['name'] }}]">
			<div class="ui icon input">
				<input type="text" 
					value="{{ $field['value'] }}" 
					name="field[{{ $id_language }}][{{ $field['name'] }}]" 
					data-type="slug" 
					data-name="{{ $field['name'] }}" 
					data-id-language="{{ $id_language }}" 
					data-slug-generate-from="{{ $field['generate_from'] }}"
					data-slug-is-empty="{{ mb_strlen($field['value']) == 0 ? 'true' : 'false' }}" />
				<i class="icon"></i>
			</div>
			<div class="error-label ui pointing red basic label"></div>
		</div>
	</td>
</tr>
