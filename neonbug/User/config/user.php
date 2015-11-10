<?php return [
	
	'model' => '\Neonbug\Common\Models\User', 
	'supports_preview' => false, 
	
	'list' => [
		'fields' => [
			'id_user' => [
				'type' => 'text'
			], 
			'name' => [
				'type' => 'text'
			], 
			'username' => [
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
		], 
		'language_independent_fields' => [
			[
				'name' => 'username', 
				'type' => 'single_line_text', 
				'value' => ''
			], 
			[
				'name' => 'password', 
				'type' => 'single_line_text', 
				'value' => ''
			], 
			[
				'name' => 'name', 
				'type' => 'single_line_text', 
				'value' => ''
			], 
			[
				'name' => 'role', 
				'type' => 'user_admin::add_fields.role', 
				'value' => ''
			]
		]
	], 
	
	'edit' => [
		'language_dependent_fields' => [
		], 
		'language_independent_fields' => [
			[
				'name' => 'username', 
				'type' => 'single_line_text', 
				'value' => ''
			], 
			[
				'name' => 'password', 
				'type' => 'single_line_text', 
				'value' => '', 
				'placeholder' => 'user::admin.password-placeholder'
			], 
			[
				'name' => 'name', 
				'type' => 'single_line_text', 
				'value' => ''
			], 
			[
				'name' => 'role', 
				'type' => 'user_admin::add_fields.role', 
				'value' => ''
			]
		]
	]
	
];
