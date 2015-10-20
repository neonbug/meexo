<?php return [
	
	'model' => '\Neonbug\Text\Models\Text', 
	
	/*
	|--------------------------------------------------------------------------
	| Slug routes at root
	|--------------------------------------------------------------------------
	|
	| Set this to TRUE, if you want routes with slugs to be accessible from 
	| root as well.
	|
	| Example: an item with slug 'abc' is accessible by route /en/content/abc
	| if slug_routes_at_root is set to TRUE, then it is also accessible by 
	| route /en/abc.
	|
	| Warning: setting this to TRUE will cause named routes 
	| (e.g. text::slug::abc) to route to root slugs (e.g. /en/abc), instead 
	| of the default ones (e.g. /en/content/abc).
	| Default routes are then accessible using alternative name, where slug 
	| is replaced by slug-default (e.g. text::slug-default::abc).
	|
	*/
	
	'slug_routes_at_root' => false, 
	
	'list' => [
		'fields' => [
			'id_text' => [
				'type' => 'text'
			], 
			'title' => [
				'type' => 'text'
			], 
			'slug' => [
				'type' => 'text', 
				'important' => false
			], 
			'updated_at' => [
				'type' => 'date', 
				'important' => false
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
			]
		]
	]
	
];
