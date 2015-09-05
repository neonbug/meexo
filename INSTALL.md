# Installation #

## 1. Requirements ##

* PHP >= 5.4
* Mcrypt PHP Extension
* OpenSSL PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* Intl PHP extension

## 2. Download ##

### 2.1. Install using composer

* Issue this command in your terminal:

	To install the latest dev version, issue this:
		
		composer create-project -s dev neonbug/meexo {directory}
		# answer Yes when asked "Do you want to remove the existing VCS (.git, .svn..) history?"
	
	To install the latest stable version, issue this:
		
		composer create-project neonbug/meexo {directory}

### 2.2. Manually install

* Download the files from `https://github.com/neonbug/meexo` into a directory. 

* Copy `.env.example` file and name it `.env`.

* Issue `composer update` command in your terminal.
 
## 3. Configure

### 3.1. .env file

* Change `APP_KEY` constant to a random value (only necessary when manually installing).

* Change `DB_*` constants.

### 3.2. config/app.php file

* Change `url` to match your environment.

* Add available languages to `available_locales` array and change the default language by changing `default_locale` value.

## 4. Add packages

* Open `config/app.php` file and add packages to `$package_providers` array. E.g. for News package add `'Neonbug\News\Providers\ServiceProvider'`.

* FUTURE USE: Add packages to `composer.json` (e.g. //*TODO write this*// and issue `composer install` command in your terminal.

## 5. Copy package files to proper locations and initialize database

Issue these commands in your terminal:
```
composer dump-autoload
php artisan vendor:publish
```

### 5.1. Add languages

Open `config/neonbug/common.php` file and add any languages to the `languages` array.

E.g. if you add Slovenščina, the file will look like this:

```
<?php

return [
	
	'languages' => [
		'en' => 'English', 
		'sl' => 'Slovenščina'
	]
	
];
```

**Make sure the database you set in `.env` file exists**, then issue this command in your terminal:

```
php artisan migrate
```

## 6. Writable directories

//TODO: write this section

## 7. Test

Migration created a default user, named Administrator. You can use it to log into the admin area.

Visit `http://yoursite/admin` and enter `admin` as username and `admin` again as password. Click Login.

**After logging in, change your password!**

## 8. Start coding

You can start by customizing the News package views. Since you can not modify any files in `neonbug` directory, `migrate` command copies those files into your app.

E.g. you can find News views in `resources/views/vendor/news/` directory.

## 9. Extending Neonbug packages

//TODO: write this section

## 10. Writing your own Neonbug packages

//TODO: write this section
