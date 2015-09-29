<?php return [
	
	'model' => '\Neonbug\Common\Models\Translation', 
	'model_source' => '\Neonbug\Common\Models\TranslationSource', 
	
	'list' => [
		'fields' => [
			'id_translation' => [
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
			]
		], 
		'language_independent_fields' => [
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
			]
		], 
		'language_independent_fields' => [
		]
	]
	
];
