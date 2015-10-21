
return [
	
	'model' => '\Neonbug\{{ $package_name }}\Models\{{ $model_name }}', 
	
	'list' => [
		'fields' => [
			'{{ 'id_' . str_replace('\\', '', snake_case($model_name)) }}' => [
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
