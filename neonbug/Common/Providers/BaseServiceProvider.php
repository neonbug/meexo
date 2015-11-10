<?php namespace Neonbug\Common\Providers;

use \Illuminate\Routing\Router as Router;

abstract class BaseServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
	 * The paths that should be published.
	 *
	 * @var array
	 */
	protected static $publishesAdmin = [];

	/**
	 * The paths that should be published by group.
	 *
	 * @var array
	 */
	protected static $publishGroupsAdmin = [];
	
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

	/**
	 * Register paths to be published by the publish command.
	 *
	 * @param  array  $paths
	 * @param  string  $group
	 * @return void
	 */
	protected function publishesAdmin(array $paths, $group = null)
	{
		$class = get_class($this);

		if ( ! array_key_exists($class, static::$publishesAdmin))
		{
			static::$publishesAdmin[$class] = [];
		}

		static::$publishesAdmin[$class] = array_merge(static::$publishesAdmin[$class], $paths);

		if ($group)
		{
			static::$publishGroupsAdmin[$group] = $paths;
		}
	}

	/**
	 * Get the paths to publish.
	 *
	 * @param  string  $provider
	 * @param  string  $group
	 * @return array
	 */
	public static function pathsToPublishAdmin($provider = null, $group = null)
	{
		if ($group && array_key_exists($group, static::$publishGroupsAdmin))
		{
			return static::$publishGroupsAdmin[$group];
		}

		if ($provider && array_key_exists($provider, static::$publishesAdmin))
		{
			return static::$publishesAdmin[$provider];
		}

		if ($group || $provider)
		{
			return [];
		}

		$paths = [];

		foreach (static::$publishesAdmin as $class => $publish)
		{
			$paths = array_merge($paths, $publish);
		}

		return $paths;
	}
}
