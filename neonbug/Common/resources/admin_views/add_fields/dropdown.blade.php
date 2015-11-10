<tr class="top aligned field-single-line-text">
	<th class="collapsing">
		{{ $field_title }}
	</th>
	<td>
		<div class="field">
			<select class="ui search dropdown" name="field[{{ $id_language }}][{{ $field['name'] }}]">
				@foreach ($field['values'] as $key=>$title)
					<option value="{{ $key }}" 
						{{ array_key_exists('value', $field) && $key == $field['value'] ? 'selected' : '' }}>
						{{ $title }}
					</option>
				@endforeach
			</select>
		</div>
	</td>
</tr>
