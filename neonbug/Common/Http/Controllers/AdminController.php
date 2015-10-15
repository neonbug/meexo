<?php namespace Neonbug\Common\Http\Controllers;

use App;
use Config;

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
		$google_account = Config::get('neonbug.common.analytics.google_account');
		$analytics_supported = ($google_account != null && $google_account != '');
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(static::PACKAGE_NAME, 'admin.index', [
				'analytics_supported' => $analytics_supported
			]);
	}
	
	public function getAnalyticsData()
	{
		$google_account     = Config::get('neonbug.common.analytics.google_account');
		$google_certificate = Config::get('neonbug.common.analytics.google_certificate');
		$default_profile_id = Config::get('neonbug.common.analytics.default_profile_id');
		
		$google_analytics_repo = new \Neonbug\Common\Repositories\GoogleAnalyticsRepository(
			$google_account, 
			$google_certificate
		);
		$rows = $google_analytics_repo->getDailyForLastThirtyDays($default_profile_id);
		
		return [ 
			'total_sessions' => array_reduce($rows, function($c, $item) { return $c + $item['sessions']; }, 0), 
			'total_views'    => array_reduce($rows, function($c, $item) { return $c + $item['views']; }, 0), 
			'highest_value'  => array_reduce($rows, function($c, $item) { 
				return max($c, $item['sessions'], $item['views']);
			}, 0), 
			'data'           => $rows
		];
	}

}
