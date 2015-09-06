<tr>
	<td class="collapsing">
		{{ $field_title }}
	</td>
	<td>
		<div class="field">
			<input type="hidden" name="field[{{ $id_language }}][{{ $field['name'] }}]" 
				value="{{ ($field['value'] == true ? 'true' : 'false') }}"
				data-name="{{ $field['name'] }}" />

			<div class="ui checkbox">
				<input type="checkbox" class="hidden" data-name="field[{{ $id_language }}][{{ $field['name'] }}]" 
					{!! ($field['value'] == true ? 'checked="checked"' : '') !!} />
			</div>
		</div>
	</td>
</tr>
