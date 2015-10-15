<?php

return [
	
	/*
	|--------------------------------------------------------------------------
	| Languages Configuration
	|--------------------------------------------------------------------------
	|
	| Set all supported languages here (admin and front end).
	| 
	| It's important to do so before migrating packages, or you will have to 
	| run insert_*_translations migrations again manually.
	|
	*/
	
	'languages' => [
		'en' => 'English'
	], 
	
	/*
	|--------------------------------------------------------------------------
	| Analytics Configuration
	|--------------------------------------------------------------------------
	|
	| Set your Google account and Google certificate you received in 
	| Developers Console.
	|
	| Google account should be in the form of 
	| RANDOM_LETTERS@developer.gserviceaccount.com, 
	| Google certificate should be the full path to a .p12 certificate.
	|
	| To receive your account and certificate, please follow instructions at 
	| https://developers.google.com/analytics/devguides/reporting/core/v3/quickstart/service-php
	|
	| You also need to set a Default Profile Id, which is an 8 digit number, 
	| identifying a Google Analytics account.
	| Example: if your Google Analytics account is UA-41534519-1, 
	| your Default Profile Id should be 41534519.
	| 
	| Example:
	| 'google_account'     => '36744352652-aekjq1eiq7q4ojqhpbfa8op8p0f7dmdu@developer.gserviceaccount.com', 
	| 'google_certificate' => __DIR__ . '/../../resources/assets/analytics_key.p12', 
	| 'default_profile_id' => '41764974'
	|
	*/
	
	'analytics' => [
		'google_account'     => '', 
		'google_certificate' => '', 
		'default_profile_id' => ''
	], 
	
];
