<td class="collapsing">
	{{ $field['name'] }}
</td>
<td>
	<div class="field">
		<input type="text" name="field[{{ $id_language }}][{{ $field['name'] }}]" 
			value="{{ date('Y-m-d', strtotime($field['value'])) }}" data-name="{{ $field['name'] }}" />
	</div>
</td>
