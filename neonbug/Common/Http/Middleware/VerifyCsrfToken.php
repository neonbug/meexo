<?php namespace Neonbug\Common\Http\Middleware;

use Closure;
use Redirect;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends BaseVerifier {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 *
	 * @throws \Illuminate\Session\TokenMismatchException
	 */
	public function handle($request, Closure $next)
	{
		if ($this->isReading($request) || ($request->session()->has('_token') && $this->tokensMatch($request)))
		{
			$response = $next($request);
			
			if ($this->isReading($request) && $request->session()->has('_token'))
			{
				$response = $this->addCookieToResponse($request, $response);
			}
			
			return $response;
		}

		//throw new TokenMismatchException;
		return Redirect::back()->withInput()->withErrors([ 'general' => 'Your session has expired' ]);
	}

}
