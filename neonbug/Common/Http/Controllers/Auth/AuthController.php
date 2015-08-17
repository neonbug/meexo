<?php namespace Neonbug\Common\Http\Controllers\Auth;

use Neonbug\Common\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;

use App;

class AuthController extends Controller {

	const PACKAGE_NAME = 'common';
	
	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	/**
	 * The Guard implementation.
	 *
	 * @var \Illuminate\Contracts\Auth\Guard
	 */
	protected $auth;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Show the application login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLogin()
	{
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(static::PACKAGE_NAME, 'auth.login', []);
	}

	/**
	 * Handle a login request to the application.
	 * 
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postLogin(\Illuminate\Http\Request $request)
	{
		$this->validate($request, [
			'username' => 'required', 'password' => 'required',
		]);
		
		$return_url = $request->input('return_url', '');
		if ($return_url == '') $return_url = null;

		$credentials = $request->only('username', 'password');
		
		if ($this->auth->attempt($credentials, $request->has('remember')))
		{
			return ($return_url != null ? redirect($return_url) : redirect()->route('admin-home'));
		}
		
		return redirect(route('admin-login') . '?return_url=' . urlencode($return_url))
			->withInput($request->only('username', 'remember'))
			->withErrors([
				'username' => 'Wrong username or password',
			]);
	}

	/**
	 * Log the user out of the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLogout()
	{
		$this->auth->logout();
		
		return redirect()->route('admin-login');
	}

}
