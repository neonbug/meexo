<?php namespace Neonbug\Common\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App;
use Session;

class AdminMenu {

	/**
	 * Create a new filter instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		return $next($request);
	}

}
