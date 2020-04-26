<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'Neonbug\Common\Http\Middleware\VerifyCsrfToken',
	];

	/**
	 * The application's route middleware groups.
	 *
	 * @var array
	 */
	protected $middlewareGroups = [
		'web' => [
			\App\Http\Middleware\EncryptCookies::class,
			\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
			\Illuminate\Session\Middleware\StartSession::class,
			\Illuminate\View\Middleware\ShareErrorsFromSession::class,
			\App\Http\Middleware\VerifyCsrfToken::class,
			\Illuminate\Routing\Middleware\SubstituteBindings::class,
		],

		'api' => [
			'throttle:60,1',
			'bindings',
		],
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth'       => \App\Http\Middleware\Authenticate::class,
		'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
		'bindings'   => \Illuminate\Routing\Middleware\SubstituteBindings::class,
		'can'        => \Illuminate\Auth\Middleware\Authorize::class,
		'guest'      => \App\Http\Middleware\RedirectIfAuthenticated::class,
		'throttle'   => \Illuminate\Routing\Middleware\ThrottleRequests::class,
		'auth.admin' => \Neonbug\Common\Http\Middleware\AuthenticateAdmin::class,
		'admin.menu' => \Neonbug\Common\Http\Middleware\AdminMenu::class,
		'online'     => \Neonbug\Common\Http\Middleware\Online::class,
	];

}
