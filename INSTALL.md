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

* Change `APP_URL` to match your environment.

* Change `APP_KEY` constant to a random value of 32 characters (only necessary when manually installing).

* Change `DB_*` constants.

### 3.2. config/app.php file

* Add available languages to `available_locales` and `admin_available_locales` arrays and change the default languages by changing `default_locale` and `admin_default_locale` values.

## 4. Add packages

* Open `config/app.php` file and add packages to `$package_providers` array. E.g. for News package add `'Neonbug\News\Providers\ServiceProvider'`.

* Add packages to `composer.json` file (e.g. for News package add `"neonbug/meexo-news": "0.*"` to `require`). See `https://packagist.org/packages/neonbug/` for a list of all Neonbug packages. Issue `composer update` command in your terminal to install newly added packages.

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

You can start by customizing the News package views. Since you can not modify any files in `vendor/neonbug` directory, `migrate` command copies those files into your app.

E.g. you can find News views in `resources/views/vendor/news/` directory.

## 9. Extending Meexo packages

//TODO: extend this section

### 9.1. Customizing package admin views

To modify a package's admin views, you need to publish them first (i.e. copy them to appropriate folder).

To make it easier, you can use `vendor:publish-admin` artisan command. Views are copied to `/resources/views/vendor/packagename` folder.

Example:

Running `php artisan vendor:publish-admin --provider="Neonbug\Gallery\Providers\ServiceProvider"` copies Gallery views to folder `/resources/views/vendor/gallery_admin`.

## 10. Writing your own Meexo packages

There are two types of packages: Neonbug packages and App packages.

Neonbug packages are meant to be used on multiple sites, they reside in separate Git repositories, they are published to Packagist and installed using Composer, which installs them under `vendor/` folder.

On the other hand, App packages are meant for one specific site and they reside in `app/Packages/` folder. They are also meant to be commited to site's repository.

Both types of packages can be created using `make:meexo-package` command and the only difference (besides the above ones) is the namespace: Neonbug packages are created in `\Neonbug\` namespace, while App packages are created in `\App\Packages\` namespace. 

### 10.1. Creating your own package

To create your own package, issue this command in your terminal:

```
php artisan make:meexo-package PackageName
```

Now simply follow the instructions.

### 10.2. Creating your own add/edit field type
  
To create your own field type, copy an existing field view from `/vendor/neonbug/meexo-common/resources/admin_views/add_fields/` to your package's views directory, e.g. `/app/Packages/YourPackageName/resources/admin_views/add_fields/`.

To use it, edit the appropriate config file. Instead of using a common field type (e.g. `single_line_text`) in field's `type` property, enter the full path to your field view, e.g. `your_package_name_admin::add_fields.custom_field_type`.

## 11. FAQ

### 11.1. What happens if I already migrated everything and want to add another language?

As stated in sections `3.2. config/app.php file` and `5.1. Add languages` you need to add the new language to `config/app.php` and `config/neonbug/common.php` files.

Afterwards, you will need to rerun the migrations. But since those migrations have already been ran, you first need to remove them manually from the DB (there will be an artisan command for this in the future).

To remove existing migrations from DB, execute this query: `DELETE FROM migrations WHERE migration LIKE '%_insert_%_translations'`.

When done, you can now rerun the migrations by issuing `php artisan migrate` in your terminal.
