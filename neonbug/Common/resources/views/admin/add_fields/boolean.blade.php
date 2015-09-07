<tr class="top aligned field-boolean">
	<th class="collapsing">
		{{ $field_title }}
	</th>
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
