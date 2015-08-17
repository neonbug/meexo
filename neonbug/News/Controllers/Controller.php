<?php namespace Neonbug\News\Controllers;

use App;
use \Neonbug\News\Models\News as News;

class Controller extends \App\Http\Controllers\Controller {
	
	const PACKAGE_NAME = 'news';
	
	public function __construct()
	{
	}
	
	public function index()
	{
		$news_repo = App::make('\Neonbug\News\Repositories\NewsRepository');
		$items = $news_repo->getLatest();
		
		$language = App::make('Language');
		App::make('ResourceRepository')->inflateObjectsWithValues($items, $language->id_language);
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(static::PACKAGE_NAME, 'index', [ 'items' => $items ]);
	}
	
	public function item($id)
	{
		$item = News::findOrFail($id);
		if (!$item->published || 
			date('Y-m-d') < date('Y-m-d', strtotime($item->published_from_date)))
		{
			abort(404);
		}
		
		$language = App::make('Language');
		App::make('ResourceRepository')->inflateObjectWithValues($item, $language->id_language);
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(static::PACKAGE_NAME, 'item', [ 'item' => $item ]);
	}
	
}
