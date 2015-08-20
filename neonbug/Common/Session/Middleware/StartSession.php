<?php namespace Neonbug\Common\Session\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use Illuminate\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Session\CookieSessionHandler;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Routing\TerminableMiddleware;

class StartSession extends \Illuminate\Session\Middleware\StartSession {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$this->sessionHandled = true;

		// If a session driver has been configured, we will need to start the session here
		// so that the data is ready for an application. Note that the Laravel sessions
		// do not make use of PHP "native" sessions in any way since they are crappy.
		if ($this->sessionConfigured())
		{
			$session = $this->startSession($request);

			$request->setSession($session);
		}

		$response = $next($request);

		// Again, if the session has been configured we will need to close out the session
		// so that the attributes may be persisted to some storage medium. We will also
		// add the session identifier cookie to the application response headers now.
		if ($this->sessionConfigured())
		{
			$this->storeCurrentUrl($request, $session);

			$this->collectGarbage($session);

			$found_user_session = false;
			foreach ($session->all() as $key=>$value)
			{
				if ($key == '_previous') continue;
				if ($key == 'flash' && sizeof($value['new']) == 0) continue;
				
				$found_user_session = true;
				break;
			}
			
			if ($found_user_session) $this->addCookieToResponse($response, $session);
		}

		return $response;
	}

}
