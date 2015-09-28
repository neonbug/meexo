<?php

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
    if (Request::segment(2) != 'admin' && !App::runningInConsole())
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
    if (Request::segment(2) == 'admin' && !App::runningInConsole())
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

App::singleton('Language', function() use($language) { return $language; });
App::singleton('AdminLanguage', function() use($admin_language) { return $admin_language; });
App::singleton('LanguageRepository', '\Neonbug\Common\Repositories\LanguageRepository');

//frontend
Route::group(['middleware' => ['online']], function() use ($locale)
{
    Route::get('/', 'HomeController@index'); //special route without locale prefix
    Route::group(['prefix' => $locale], function()
    {
        Route::get('/', 'HomeController@index');
    });
});