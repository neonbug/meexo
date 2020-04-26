<?php

use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------
| PHP ERROR REPORTING LEVEL
|---------------------------------------------------------------
|
| Disable deprecation warnings for PHP 7.1 and above, 
| since PHP 7.1 deprecates mcrypt.
|
*/
if (version_compare(PHP_VERSION, '7.1.0', '>='))
{
	error_reporting(E_ALL ^ E_DEPRECATED);
}
else
{
	error_reporting(E_ALL);
}

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* get locale from URL */
$locale = Request::segment(1);
if ($locale == null)
{
    $locale = Config::get('app.default_locale');
}
else if (!in_array($locale, Config::get('app.available_locales')))
{
    if (Request::segment(2) != 'admin' && !App::runningInConsole() && 
    	mb_strlen($locale) == 2 /* only consider strings that could be locales, ignore the rest (for URLs without locale) */)
    {
        header('Location: ' . url(Config::get('app.default_locale')));
        exit();
    }
    else
    {
        $locale = Config::get('app.default_locale');
    }
}
App::setLocale($locale);

$admin_locale = Request::segment(1);
if ($admin_locale == null)
{
    $admin_locale = Config::get('app.admin_default_locale');
}
else if (!in_array($admin_locale, Config::get('app.admin_available_locales')))
{
    if (Request::segment(2) == 'admin' && !App::runningInConsole() && 
    	mb_strlen($locale) == 2 /* only consider strings that could be locales, ignore the rest (for URLs without locale) */)
    {
        header('Location: ' . url(Config::get('app.admin_default_locale') . '/' . Request::segment(2)));
        exit();
    }
    else
    {
        $admin_locale = Config::get('app.admin_default_locale');
    }
}

$language = null;
$admin_language = null;
if (!App::runningInConsole())
{
    $language = \Neonbug\Common\Models\Language::getByLocale($locale);
    if ($language == null) exit('Language not found');
    
    $admin_language = ($admin_locale == $locale ? $language : \Neonbug\Common\Models\Language::getByLocale($admin_locale));
    if ($admin_language == null) exit('Language not found');
}

App::singleton('\Neonbug\Common\Models\Language', function() use($language) { return $language; });
App::singleton('Language', function() use($language) { return $language; });
App::singleton('AdminLanguage', function() use($admin_language) { return $admin_language; });
App::singleton('LanguageRepository', '\Neonbug\Common\Repositories\LanguageRepository');

//frontend
Route::group(['middleware' => ['online']], function() use ($locale)
{
    Route::get('/', [ 'as' => 'home', 'uses' => 'HomeController@index' ]); //special route without locale prefix
    Route::get('/' . $locale, [ 'as' => 'current-home', 'uses' => 'HomeController@index' ]);
    
    Route::group(['prefix' => $locale], function()
    {
        Route::get('/', 'HomeController@index');
    });
});