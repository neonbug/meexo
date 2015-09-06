<tr>
	<td class="collapsing">
		{{ $field_title }}
	</td>
	<td>
		<div class="field">
			<input type="text" value="{{ $formatter->formatShortDate(strtotime($field['value'])) }}" 
				data-name="{{ $field['name'] }}" data-type="date" 
				data-date-rel="field[{{ $id_language }}][{{ $field['name'] }}]" />
			<input type="hidden" name="field[{{ $id_language }}][{{ $field['name'] }}]" 
				value="{{ date('Y-m-d', strtotime($field['value'])) }}" />
		</div>
	</td>
</tr>
