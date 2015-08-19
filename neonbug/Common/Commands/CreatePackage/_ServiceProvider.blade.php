namespace Neonbug\{{ $package_name }}\Providers;

use App;
use Route;
use View;
use \Illuminate\Routing\Router as Router;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {
	
	const PACKAGE_NAME     = '{{ $lowercase_package_name }}';
	const PREFIX           = '{{ $route_prefix }}';
	const ROLE             = '{{ $table_name }}';
	const TABLE_NAME       = '{{ $table_name }}';
	const CONTROLLER       = '\Neonbug\{{ $package_name }}\Controllers\Controller';
	const ADMIN_CONTROLLER = '\Neonbug\{{ $package_name }}\Controllers\AdminController';
	
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
		
		$this->publishes([
			__DIR__.'/../database/migrations/' => database_path('/migrations')
		], 'migrations');
		
		$this->publishes([
			__DIR__.'/../config/' . static::PACKAGE_NAME . '.php' => config_path('neonbug/' . static::PACKAGE_NAME . '.php'),
		]);
		
		//============
		//== ROUTES ==
		//============
		$locale = App::getLocale();
		
		$resource_repo 	= App::make('ResourceRepository');
		$language 		= App::make('Language');
		
		//frontend
		$router->group([ 'middleware' => [ 'online' ], 'prefix' => $locale . '/' . static::PREFIX ], 
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
		$router->group([ 'prefix' => $locale . '/admin/' . static::PREFIX, 'middleware' => [ 'auth.admin' ], 
			'role' => static::ROLE ], function($router)
		{
			$router->get('list', [
				'as'   => static::PREFIX . '::admin::list', 
				'uses' => static::ADMIN_CONTROLLER . '@admin_list'
			]);
			
			$router->get('add', [
				'as'   => static::PREFIX . '::admin::add', 
				'uses' => static::ADMIN_CONTROLLER . '@admin_add'
			]);
			$router->post('add', [
				'as'   => static::PREFIX . '::admin::add-save', 
				'uses' => static::ADMIN_CONTROLLER . '@admin_add_post'
			]);
			
			$router->get('edit/{id}', [
				'as'   => static::PREFIX . '::admin::edit', 
				'uses' => static::ADMIN_CONTROLLER . '@admin_edit'
			]);
			$router->post('edit/{id}', [
				'as'   => static::PREFIX . '::admin::edit-save', 
				'uses' => static::ADMIN_CONTROLLER . '@admin_edit_post'
			]);
			
			$router->post('delete', [
				'as'   => static::PREFIX . '::admin::delete', 
				'uses' => static::ADMIN_CONTROLLER . '@admin_delete_post'
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
		if (!App::bound('\Neonbug\{{ $package_name }}\Repositories\{{ $model_name }}Repository'))
		{
			App::singleton('\Neonbug\{{ $package_name }}\Repositories\{{ $model_name }}Repository', '\Neonbug\{{ $package_name }}\Repositories\{{ $model_name }}Repository');
		}
	}
	
	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		dd('map');
		
	}

}
