<?php return [
	
	'model' => '\Neonbug\Gallery\Models\Gallery', 
	
	'list' => [
		'fields' => [
			'id_gallery' => [
				'type' => 'text'
			], 
			'title' => [
				'type' => 'text'
			], 
			'slug' => [
				'type' => 'text'
			], 
			'updated_at' => [
				'type' => 'date'
			], 
			'published' => [
				'type' => 'boolean'
			], 
			'ord' => [
				'type' => 'text'
			]
		]
	], 
	
	'add' => [
		'language_dependent_fields' => [
			[
				'name' => 'title', 
				'type' => 'single_line_text', 
				'value' => ''
			], 
			[
				'name' => 'slug', 
				'type' => 'slug', 
				'value' => '', 
				'generate_from' => 'title'
			], 
			[
				'name' => 'contents', 
				'type' => 'rich_text', 
				'value' => ''
			]
		], 
		'language_independent_fields' => [
			[
				'name' => 'main_image', 
				'type' => 'image', 
				'value' => ''
			], 
			[
				'name' => 'published', 
				'type' => 'boolean', 
				'value' => true
			], 
			[
				'name' => 'ord', 
				'type' => 'integer', 
				'value' => '1'
			], 
			[
				'name' => 'images', 
				'type' => 'gallery::admin.add_fields.gallery_images', 
				'value' => ''
			]
		]
	], 
	
	'edit' => [
		'language_dependent_fields' => [
			[
				'name' => 'title', 
				'type' => 'single_line_text', 
				'value' => ''
			], 
			[
				'name' => 'slug', 
				'type' => 'slug', 
				'value' => '', 
				'generate_from' => 'title'
			], 
			[
				'name' => 'contents', 
				'type' => 'rich_text', 
				'value' => ''
			]
		], 
		'language_independent_fields' => [
			[
				'name' => 'main_image', 
				'type' => 'image', 
				'value' => ''
			], 
			[
				'name' => 'published', 
				'type' => 'boolean', 
				'value' => true
			], 
			[
				'name' => 'ord', 
				'type' => 'integer', 
				'value' => '1'
			], 
			[
				'name' => 'images', 
				'type' => 'gallery::admin.add_fields.gallery_images', 
				'value' => ''
			]
		]
	]
	
];