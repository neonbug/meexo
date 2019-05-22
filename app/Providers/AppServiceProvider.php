<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadTranslationsFrom('/', 'site'); //enables site translations, such as site::frontend.header.title
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
	}

}
