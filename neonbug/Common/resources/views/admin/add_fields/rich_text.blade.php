<td class="collapsing">
	{{ $field['name'] }}
</td>
<td>
	<div class="field">
		<textarea name="field[{{ $id_language }}][{{ $field['name'] }}]" 
			data-name="{{ $field['name'] }}">{{ $field['value'] }}</textarea>
	</div>
</td>
