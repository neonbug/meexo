<?php
$p = 'common::admin.';
return [
	// add
	$p . 'add.tab-title-general'         => [ 'en' => 'General', 'sl' => 'Splošno' ], 
	$p . 'add.save-button'               => [ 'en' => 'Save', 'sl' => 'Shrani' ], 
	$p . 'add.preview-button'            => [ 'en' => 'Preview', 'sl' => 'Predogled' ], 
	
	$p . 'add.current-image-title'       => [ 'en' => 'Current image', 'sl' => 'Trenutna slika' ], 
	$p . 'add.current-image-remove'      => [ 'en' => 'Remove', 'sl' => 'Odstrani' ], 
	$p . 'add.current-image-description' => [ 
		'en' => 'Check the checkbox below to remove this image', 
		'sl' => 'Za odstranitev te slike, obkljukajte spodnje potrditveno polje'
	], 
	
	$p . 'add.current-file-title'       => [ 'en' => 'Current file', 'sl' => 'Trenutna datoteka' ], 
	$p . 'add.current-file-remove'      => [ 'en' => 'Remove', 'sl' => 'Odstrani' ], 
	$p . 'add.current-file-description' => [ 
		'en' => 'Check the checkbox below to remove this file', 
		'sl' => 'Za odstranitev te datoteke, obkljukajte spodnje potrditveno polje'
	], 
	
	$p . 'add.error-dialog-message' => [ 
		'en' => 'There are errors in your form. Please resolve them and try again.', 
		'sl' => 'Na vašem obrazcu so napake. Prosimo, popravite jih in poskusite znova.' ], 
	$p . 'add.error-dialog-confirm' => [ 'en' => 'Ok', 'sl' => 'V redu' ], 
	
	$p . 'add.errors.slug-empty'          => [ 'en' => 'Must not be empty', 'sl' => 'Polje ne sme biti prazno' ], 
	$p . 'add.errors.slug-already-exists' => [ 
		'en' => 'Already exists, choose another value', 
		'sl' => 'Že obstaja, vpišite drugo vrednost'
	], 
	
	$p . 'add.messages.deleted'          => [ 'en' => 'Deleted', 'sl' => 'Odstranjeno' ], 
	
	// list
	$p . 'list.edit-action'                 => [ 'en' => 'Edit', 'sl' => 'Uredi' ], 
	$p . 'list.delete-action'               => [ 'en' => 'Delete', 'sl' => 'Odstrani' ], 
	$p . 'list.delete-confirmation-message' => [ 
		'en' => 'Are you sure you want to delete that item?', 
		'sl' => 'Res želite odstraniti ta element?'
	], 
	$p . 'list.delete-confirmation-confirm' => [ 'en' => 'Yes', 'sl' => 'Da' ], 
	$p . 'list.delete-confirmation-deny'    => [ 'en' => 'No', 'sl' => 'Ne' ], 
	
	// menu
	$p . 'menu.dashboard'      => [ 'en' => 'Dashboard', 'sl' => 'Nadzorna plošča' ], 
	$p . 'menu.title'          => [ 'en' => 'Admin menu', 'sl' => 'Admin meni' ], 
	
	// dashboard
	$p . 'dashboard.title'     => [ 'en' => 'Dashboard', 'sl' => 'Nadzorna plošča' ], 
	
	// header
	$p . 'header.title'        => [ 'en' => 'Administration', 'sl' => 'Administracija' ], 
	$p . 'header.logged-in-as' => [ 
		'en' => 'Logged in as <strong>:name</strong>', 
		'sl' => 'Prijavljen/a kot <strong>:name</strong>'
	], 
	$p . 'header.logout'       => [ 'en' => 'Logout', 'sl' => 'Odjava' ], 
	
	// breadcrumbs
	$p . 'breadcrumbs.first-item' => [ 'en' => 'Home', 'sl' => 'Domov' ], 
	
	// login
	$p . 'login.title'         => [ 'en' => 'Admin login', 'sl' => 'Admin prijava' ], 
	$p . 'login.username'      => [ 'en' => 'Username', 'sl' => 'Uporabniško ime' ], 
	$p . 'login.password'      => [ 'en' => 'Password', 'sl' => 'Geslo' ], 
	$p . 'login.login-button'  => [ 'en' => 'Login', 'sl' => 'Prijava' ], 
	
	$p . 'login-popup.title'         => [ 'en' => 'Session expired', 'sl' => 'Seja je potekla' ], 
	$p . 'login-popup.username'      => [ 'en' => 'Username', 'sl' => 'Uporabniško ime' ], 
	$p . 'login-popup.password'      => [ 'en' => 'Password', 'sl' => 'Geslo' ], 
	$p . 'login-popup.login-button'  => [ 'en' => 'Login', 'sl' => 'Prijava' ], 
	$p . 'login-popup.description'   => [ 
		'en' => 'To continue working, please log in again.', 
		'sl' => 'Da lahko nadaljujete z delom, se prosim še enkrat prijavite.'
	], 
	
	$p . 'validation.required' => [ 'en' => 'The :attribute field is required.', 'sl' => 'Polje :attribute je obvezno.' ], 
	
	// messages
	$p . 'main.messages.close-page' => [ 
		'en' => 'Are you sure you want to close this page? Any unsaved changes will be gone.', 
		'sl' => 'Ste prepričani, da želite zapreti stran? Vse neshranjene spremembe bodo izgubljene.'
	], 
	$p . 'main.messages.logged-in'  => [ 'en' => 'Successfully logged in', 'sl' => 'Prijava uspešna' ], 
	$p . 'main.messages.saved'      => [ 'en' => 'Saved', 'sl' => 'Shranjeno' ], 
];
