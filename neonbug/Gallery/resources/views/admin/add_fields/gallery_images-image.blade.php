<div class="ui card gallery-images-image">
	<input type="hidden" 
		@if ($field == null || $id_language == null)
			name="" 
		@else
			name="gallery_image[{{ $id_language }}][{{ $field['name'] }}][images][]" 
		@endif
		
		@if ($image == null)
			value="" 
		@else
			value="{{ $image }}" 
		@endif
		/>
	<a class="image" target="_blank" 
		@if ($image == null)
			data-id="" 
			href="#" 
		@else
			data-id="{{ $image }}" 
			href="{{ Croppa::url('uploads/gallery/' . $item->{$item->getKeyName()} . '/' . 
				($id_language == -1 ? 0 : $id_language) . '/' . $field['name'] . '/' . $image) }}" 
			style="background-image: url('{{ Croppa::url('uploads/gallery/' . $item->{$item->getKeyName()} . '/' . 
				($id_language == -1 ? 0 : $id_language) . '/' . $field['name'] . '/' . $image, 180, 120) }}');" 
		@endif
		>
		
		@if ($image == null)
			<div class="gallery-images-image-upload-overlay-progress"></div>
			<div class="gallery-images-image-upload-overlay">
				<span class="gallery-images-image-progress">0 %</span>
			</div>
		@endif
	</a>
	<div class="content">
		<div class="description">
			<span class="gallery-images-image-name">
				{{ $image == null ? '' : $image }}
			</span>
		</div>
	</div>
	<div class="ui bottom attached red button 
		{{ $image == null ? 'gallery-images-image-cancel' : 'gallery-images-image-remove' }}">
		<i class="trash icon"></i>
		Remove
	</div>
</div>
