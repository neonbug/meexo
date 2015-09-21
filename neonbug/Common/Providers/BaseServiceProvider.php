<?php namespace Neonbug\Common\Providers;

use \Illuminate\Routing\Router as Router;

abstract class BaseServiceProvider extends \Illuminate\Support\ServiceProvider {
	
	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		self::bootTraits();
	}
	
	/**
	 * Boot all of the bootable traits on the ServiceProvider.
	 *
	 * @return void
	 */
	protected static function bootTraits()
	{
		foreach (class_uses_recursive(get_called_class()) as $trait) {
			if (method_exists(get_called_class(), $method = 'boot'.class_basename($trait))) {
				forward_static_call([get_called_class(), $method]);
				//$this->$method();
			}
		}
	}
}
