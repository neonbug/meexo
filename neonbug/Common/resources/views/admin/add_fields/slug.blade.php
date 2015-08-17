<td class="collapsing">
	{{ $field['name'] }}
</td>
<td>
	<div class="field">
		<input type="text" name="field[{{ $id_language }}][{{ $field['name'] }}]" value="{{ $field['value'] }}" 
			data-name="{{ $field['name'] }}" data-type="slug" data-slug-generate-from="title"
			data-slug-is-empty="{{ mb_strlen($field['value']) == 0 ? 'true' : 'false' }}" />
		{{-- TODO: data-slug-generate-from should be configurable! --}}
	</div>
</td>
