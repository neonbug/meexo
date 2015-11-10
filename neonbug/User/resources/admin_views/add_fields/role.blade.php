<tr class="top aligned field-role">
	<th class="collapsing">
		{{ $field_title }}
	</th>
	<td>
		<div class="fields">
			<div class="role-administrator-check three wide field">
				
				<div class="ui checkbox" data-name="role[{{ $id_language }}][{{ $field['name'] }}][]" 
					style="padding-top: 8px;">
					<input type="checkbox" name="admin_role[{{ $id_language }}][{{ $field['name'] }}]" value="true">
					<label>{{ trans('user::admin.add_fields.role.administrator') }}</label>
				</div>
				
			</div>
			<div class="thirteen wide field" data-name="role[{{ $id_language }}][{{ $field['name'] }}][]">
				<select name="role[{{ $id_language }}][{{ $field['name'] }}][]" multiple="" 
					class="ui fluid dropdown" data-type="role">
					
					<option value="">{{ trans('user::admin.add_fields.role.role-select-placeholder') }}</option>
					@foreach ($field['values'] as $key=>$val)
						<option value="{{ $key }}">{{ $val }}</option>
					@endforeach
					
				</select>
			</div>
		</div>
	</td>
</tr>
