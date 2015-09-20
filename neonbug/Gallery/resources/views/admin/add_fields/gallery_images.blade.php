<tr class="top aligned field-gallery-images">
	<th class="collapsing">
		{{ $field_title }}
	</th>
	<td>
		<div class="field" data-name="gallery_image[{{ $id_language }}][{{ $field['name'] }}]">
			<div class="gallery-images-upload-container">
				<button class="ui button gallery-images-browse" type="button">
					<i class="icon upload"></i>
					Upload ...
				</button>
				
				<div class="gallery-images-drop-target">
					<span>drag and drop your files here</span>
				</div>
				
				<div style="clear: both;"></div>
			</div>
			
			<div class="ui basic segment gallery-images-list-container">
				<div class="ui cards gallery-images-list">
					@if ($item != null)
						@foreach ($item->gallery_images[$id_language][$field['name']] as $image)
							@include('gallery::admin.add_fields.gallery_images-image', [ 'item' => $item, 
								'image' => $image->image, 'field' => $field, 'id_language' => $id_language ])
						@endforeach
					@endif
				</div>
			</div>
		</div>
	</td>
</tr>
