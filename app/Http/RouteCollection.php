<?php namespace App\Http;

class RouteCollection extends \Illuminate\Routing\RouteCollection {

	// overriden method, to allow for multiple routes with the same URI
	protected function addToCollections($route)
	{
		$domainAndUri = $route->domain().$route->getUri();
		
		foreach ($route->methods() as $method) {
			while (
				array_key_exists($method.$domainAndUri, $this->allRoutes) ||
				(
					array_key_exists($method, $this->routes) &&
					array_key_exists($domainAndUri, $this->routes[$method])
				)
			) {
				$domainAndUri .= '--' . mt_rand(1000, 9999);
			}
			$this->routes[$method][$domainAndUri] = $route;
		}
		$this->allRoutes[$method.$domainAndUri] = $route;
	}

}
