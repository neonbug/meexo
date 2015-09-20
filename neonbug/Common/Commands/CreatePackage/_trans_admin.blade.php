
$p = '{{ $lowercase_package_name }}::admin.';
return [
	$p . 'title.main' => [ 'en' => '{{ $package_name }}' ], 
	$p . 'title.list' => [ 'en' => 'List' ], 
	$p . 'title.add'  => [ 'en' => 'Add' ], 
	$p . 'title.edit' => [ 'en' => 'Edit' ], 
	
	$p . 'menu.main' => [ 'en' => '{{ $package_name }}' ], 
	$p . 'menu.list' => [ 'en' => 'List' ], 
	$p . 'menu.add'  => [ 'en' => 'Add' ], 
];
