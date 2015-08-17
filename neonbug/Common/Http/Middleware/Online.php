<?php namespace Neonbug\Common\Http\Middleware;

use Closure;

class Online {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (env('APP_ONLINE', true) === false)
		{
			return response()->view('offline');
		}
		
		return $next($request);
	}

}
