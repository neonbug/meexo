<?php

return [
	
	'list' => [
		'fields' => [
			'id_news' => [
				'type' => 'text'
			], 
			'title' => [
				'type' => 'text'
			], 
			'slug' => [
				'type' => 'text'
			], 
			'published' => [
				'type' => 'boolean'
			], 
			'published_from_date' => [
				'type' => 'date'
			], 
			'updated_at' => [
				'type' => 'date'
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
				'value' => ''
			], 
			[
				'name' => 'excerpt', 
				'type' => 'rich_text', 
				'value' => ''
			], 
			[
				'name' => 'contents', 
				'type' => 'rich_text', 
				'value' => ''
			]
		], 
		'language_independent_fields' => [
			[
				'name' => 'published_from_date', 
				'type' => 'date', 
				'value' => date('Y-m-d')
			], 
			[
				'name' => 'main_image', 
				'type' => 'image', 
				'value' => ''
			], 
			[
				'name' => 'published', 
				'type' => 'boolean', 
				'value' => true
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
				'value' => ''
			], 
			[
				'name' => 'excerpt', 
				'type' => 'rich_text', 
				'value' => ''
			], 
			[
				'name' => 'contents', 
				'type' => 'rich_text', 
				'value' => ''
			]
		], 
		'language_independent_fields' => [
			[
				'name' => 'published_from_date', 
				'type' => 'date', 
				'value' => date('Y-m-d')
			], 
			[
				'name' => 'main_image', 
				'type' => 'image', 
				'value' => ''
			], 
			[
				'name' => 'published', 
				'type' => 'boolean', 
				'value' => true
			]
		]
	]
	
];
