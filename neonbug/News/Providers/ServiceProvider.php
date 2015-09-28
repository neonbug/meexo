<?php namespace Neonbug\News\Providers;

use App;
use Route;
use View;
use \Illuminate\Routing\Router as Router;

class ServiceProvider extends \Neonbug\Common\Providers\BaseServiceProvider {
	
	const PACKAGE_NAME     = 'news';
	const PREFIX           = 'news';
	const ROLE             = 'news';
	const TABLE_NAME       = 'news';
	const CONTROLLER       = '\Neonbug\News\Controllers\Controller';
	const ADMIN_CONTROLLER = '\Neonbug\News\Controllers\AdminController';
	
	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		//============
		//== ASSETS ==
		//============
		$this->loadViewsFrom(__DIR__.'/../resources/views', static::PACKAGE_NAME);
		$this->publishes([
			__DIR__.'/../resources/views' => base_path('resources/views/vendor/' . static::PACKAGE_NAME),
		]);
		
		$this->loadTranslationsFrom('/', static::PACKAGE_NAME);
		
		$this->publishes([
			__DIR__.'/../database/migrations/' => database_path('/migrations')
		], 'migrations');
		
		$this->publishes([
			__DIR__.'/../config/' . static::PACKAGE_NAME . '.php' => config_path('neonbug/' . static::PACKAGE_NAME . '.php'),
		]);
		
		//============
		//== ROUTES ==
		//============
		$language = App::make('Language');
		$locale = ($language == null ? Config::get('app.default_locale') : $language->locale);
		
		$admin_language = App::make('AdminLanguage');
		$admin_locale = ($admin_language == null ? Config::get('app.admin_default_locale') : $admin_language->locale);
		
		$resource_repo = App::make('ResourceRepository');
		
		//frontend
		$router->group([ 'middleware' => [ 'online' ], 'prefix' => $locale . '/' . 
			trans(static::PACKAGE_NAME . '::frontend.route.prefix') ], 
			function($router) use ($locale, $resource_repo, $language)
		{
			$router->get('/',             [ 'as' => static::PREFIX . '::index',   'uses' => static::CONTROLLER . '@index' ]);
			$router->get('index',         [                                       'uses' => static::CONTROLLER . '@index' ]);
			$router->get('item/{id}',     [ 'as' => static::PREFIX . '::item',    'uses' => static::CONTROLLER . '@item' ]);
			$router->get('preview/{key}', [ 'as' => static::PREFIX . '::preview', 'uses' => static::CONTROLLER . '@preview' ]);
			
			if ($language != null)
			{
				$slugs = $resource_repo->getSlugs($language->id_language, static::TABLE_NAME);
				foreach ($slugs as $slug)
				{
					$router->get($slug->value, [ 'as' => static::PREFIX . '::slug::' . $slug->value, 
						function() use ($slug) {
						$controller = App::make(static::CONTROLLER);
						return $controller->callAction('item', [ 'id' => $slug->id_row ]);
					} ]);
				}
			}
		});
		
		//admin
		$router->group([ 'prefix' => $admin_locale . '/admin/' . static::PREFIX, 'middleware' => [ 'auth.admin', 'admin.menu' ], 
			'role' => static::ROLE, 'menu.icon' => 'book' ], function($router)
		{
			$router->get('list', [
				'as'   => static::PREFIX . '::admin::list', 
				'uses' => static::ADMIN_CONTROLLER . '@adminList'
			]);
			
			$router->get('add', [
				'as'   => static::PREFIX . '::admin::add', 
				'uses' => static::ADMIN_CONTROLLER . '@adminAdd'
			]);
			$router->post('add', [
				'as'   => static::PREFIX . '::admin::add-save', 
				'uses' => static::ADMIN_CONTROLLER . '@adminAddPost'
			]);
			
			$router->get('edit/{id}', [
				'as'   => static::PREFIX . '::admin::edit', 
				'uses' => static::ADMIN_CONTROLLER . '@adminEdit'
			]);
			$router->post('edit/{id}', [
				'as'   => static::PREFIX . '::admin::edit-save', 
				'uses' => static::ADMIN_CONTROLLER . '@adminEditPost'
			]);
		});

		$router->group([ 'prefix' => $admin_locale . '/admin/' . static::PREFIX, 'middleware' => [ 'auth.admin' ], 
			'role' => static::ROLE ], function($router)
		{
			$router->post('delete', [
				'as'   => static::PREFIX . '::admin::delete', 
				'uses' => static::ADMIN_CONTROLLER . '@adminDeletePost'
			]);
			
			$router->post('check-slug', [
				'as'   => static::PREFIX . '::admin::check-slug', 
				'uses' => static::ADMIN_CONTROLLER . '@adminCheckSlugPost'
			]);
		});

		parent::boot($router);
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		//===========
		//== BINDS ==
		//===========
		if (!App::bound('\Neonbug\News\Repositories\NewsRepository'))
		{
			App::singleton('\Neonbug\News\Repositories\NewsRepository', '\Neonbug\News\Repositories\NewsRepository');
		}
	}

}
