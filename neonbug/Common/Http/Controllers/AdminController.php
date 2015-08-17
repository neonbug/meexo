<?php namespace Neonbug\Common\Http\Controllers;

use App;

class AdminController extends Controller {

	const PACKAGE_NAME = 'common';
	
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

	public function index()
	{
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(static::PACKAGE_NAME, 'admin.index', []);
	}

}
