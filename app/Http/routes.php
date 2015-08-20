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
if ($locale == null || !in_array($locale, Config::get('app.available_locales')))
{
    $locale = Config::get('app.default_locale');
}
App::setLocale($locale);

if (App::runningInConsole())
{
    $language = null;
}
else
{
    $language = \Neonbug\Common\Models\Language::getByLocale($locale);
    if ($language == null) exit('Language not found');
}

App::singleton('Language', function() use($language) { return $language; });
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