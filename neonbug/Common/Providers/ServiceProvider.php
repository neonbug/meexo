<?php namespace Neonbug\Common\Providers;

use App;
use Route;
use View;
use Crypt;
use Auth;
use \Illuminate\Routing\Router as Router;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {
	
	const PACKAGE_NAME = 'common';
	
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
		$this->loadTranslationsFrom('/', 'common');
		
		$this->publishes([
			__DIR__.'/../database/migrations/' => database_path('/migrations')
		], 'migrations');
		
		$this->publishes([
			__DIR__.'/../assets/' => public_path('vendor/common'),
		], 'public');
		
		//============
		//== ROUTES ==
		//============
		$language = App::make('Language');
		$locale = ($language == null ? 'en' : $language->locale);
		
		View::composer('common::admin', function($view) use ($router)
		{
			$menu_items = [];
			
			$routes = $router->getRoutes();
			foreach ($routes as $route)
			{
				if (stripos($route->getName(), '::admin::') !== false)
				{
					if (!in_array('GET', $route->getMethods())) continue; //only list routes for GET
					if (sizeof($route->parameterNames()) > 0) continue;
					
					//check for required role
					$action = $route->getAction();
					if (!array_key_exists('role', $action)) continue;
					
					$required_role = $action['role'];
					if ($required_role != '*')
					{
						$roles = Auth::user()->roles;
						$has_role = false;
						foreach ($roles as $role)
						{
							if ($role->id_role == 'admin' || /* admin has access to everything */
								$role->id_role == $required_role)
							{
								$has_role = true;
								break;
							}
						}
						if (!$has_role) continue;
					}
					
					//transform the route into menu item
					$arr = explode('::', $route->getName());
					if (!array_key_exists($arr[0], $menu_items))
					{
						$menu_items[$arr[0]] = [
							'title' => $arr[0], 
							'items' => []
						];
					}
					$menu_items[$arr[0]]['items'][] = [
						'route' => $route->getName(), 
						'title' => $arr[2]
					];
				}
			}
			
			$view->menu_items = array_values($menu_items);
			$view->withEncryptedCsrfToken(Crypt::encrypt(csrf_token()));
			$view->withUser(Auth::user());
		});

		//admin
		$router->get('admin', function() { return redirect(route('admin-home')); });
		$router->group(['prefix' => $locale . '/admin'], function($router)
		{
			$auth_controller = '\Neonbug\Common\Http\Controllers\Auth\AuthController';
			
			$router->get('login',  ['as' => 'admin-login',  'uses' => $auth_controller . '@getLogin']);
			$router->post('login', [                        'uses' => $auth_controller . '@postLogin']);
			$router->get('logout', ['as' => 'admin-logout', 'uses' => $auth_controller . '@getLogout']);
		});
		
		$router->group(['prefix' => $locale . '/admin', 'middleware' => ['auth.admin']], function($router)
		{
			$router->group(['role' => '*'], function($router) {
				$router->get('/', ['as' => 'admin-home', 
					'uses' => '\Neonbug\Common\Http\Controllers\AdminController@index']);
			});
		});
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
		$this->app->singleton('session', function($app) { return new \Neonbug\Common\Session\SessionManager($app); });
		$this->app->singleton('Illuminate\Contracts\Debug\ExceptionHandler', '\Neonbug\Common\Exceptions\Handler');
		$this->app->singleton('Illuminate\Session\Middleware\StartSession', '\Neonbug\Common\Session\Middleware\StartSession');
		
		$this->app->bindShared('url', function($app)
		{
			$routes = $app['router']->getRoutes();

			// The URL generator needs the route collection that exists on the router.
			// Keep in mind this is an object, so we're passing by references here
			// and all the registered routes will be available to the generator.
			$app->instance('routes', $routes);

			$url = new \Neonbug\Common\Routing\UrlGenerator(
				$routes, $app->rebinding(
					'request', function($app, $request) {
						$app['url']->setRequest($request);
					}
				)
			);

			$url->setSessionResolver(function()
			{
				return $this->app['session'];
			});

			// If the route collection is "rebound", for example, when the routes stay
			// cached for the application, we will need to rebind the routes on the
			// URL generator instance so it has the latest version of the routes.
			$app->rebinding('routes', function($app, $routes)
			{
				$app['url']->setRoutes($routes);
			});

			return $url;
		});
		
		if (!$this->app->bound('ResourceRepository'))
		{
			$this->app->singleton('ResourceRepository', '\Neonbug\Common\Repositories\ResourceRepository');
		}
		
		if (!$this->app->bound('\Neonbug\Common\Helpers\AdminHelper'))
		{
			$this->app->singleton('\Neonbug\Common\Helpers\AdminHelper', '\Neonbug\Common\Helpers\AdminHelper');
		}
		
		if (!$this->app->bound('\Neonbug\Common\Helpers\CommonHelper'))
		{
			$this->app->singleton('\Neonbug\Common\Helpers\CommonHelper', '\Neonbug\Common\Helpers\CommonHelper');
		}
		
		if (!$this->app->bound('\Neonbug\Common\Helpers\MigrationHelper'))
		{
			$this->app->singleton('\Neonbug\Common\Helpers\MigrationHelper', '\Neonbug\Common\Helpers\MigrationHelper');
		}
		
		include __DIR__ . '/../helpers.php';
	}

}
