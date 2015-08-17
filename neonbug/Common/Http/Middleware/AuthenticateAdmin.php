<?php namespace Neonbug\Common\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App;
use Session;

class AuthenticateAdmin {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
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
		//check for user's role
		$has_role = false;
		if (!$this->auth->guest() && $this->auth->user() != null)
		{
			$required_role = $request->route()->getAction()['role'];
			if ($required_role == '*')
			{
				$has_role = true;
			}
			else
			{
				$roles = $this->auth->user()->roles;
				foreach ($roles as $role)
				{
					if ($role->id_role == 'admin' || /* admin has access to everything */
						$role->id_role == $required_role)
					{
						$has_role = true;
						break;
					}
				}
			}
		}
		
		if ($this->auth->guest() || !$has_role)
		{
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
				return redirect(route('admin-login') . '?return_url=' . urlencode($request->url()));
			}
		}

		return $next($request);
	}

}
