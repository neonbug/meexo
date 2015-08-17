
return [
	
	'list' => [
		'fields' => [
			'{{ 'id_' . str_replace('\\', '', snake_case($model_name)) }}' => [
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
				'value' => ''
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
				'value' => ''
			]
		], 
		'language_independent_fields' => [
		]
	]
	
];
