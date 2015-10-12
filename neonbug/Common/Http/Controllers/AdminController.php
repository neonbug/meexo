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
	
	public function getAnalyticsData()
	{
		$google_analytics_repo = new \Neonbug\Common\Repositories\GoogleAnalyticsRepository(
			'36744352652-aekjq1eiq7q4ojqhpbfa8op8p0f7dmdu@developer.gserviceaccount.com', 
			__DIR__ . '/../../../../resources/assets/analytics_key.p12'
		);
		$rows = $google_analytics_repo->getDailyForLastThirtyDays('41764974');
		
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
