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
				'name' => 'translation', 
				'type' => 'translation_admin::add_fields.translation_text', 
				'value' => ''
			]
		], 
		'language_independent_fields' => [
			[
				'name' => 'id_translation_source', 
				'type' => 'label', 
				'value' => ''
			]
		]
	], 
	
	'edit' => [
		'language_dependent_fields' => [
			[
				'name' => 'translation', 
				'type' => 'translation_admin::add_fields.translation_text', 
				'value' => ''
			]
		], 
		'language_independent_fields' => [
			[
				'name' => 'id_translation_source', 
				'type' => 'label', 
				'value' => ''
			]
		]
	]
	
];
