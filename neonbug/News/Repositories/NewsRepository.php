<?php namespace Neonbug\News\Repositories;

class NewsRepository {
	
	protected $latest_items_limit = 20;
	
	public function getLatest()
	{
		return \Neonbug\News\Models\News::where('published', true)
			->where('published_from_date', '<=', date('Y-m-d'))
			->orderBy('published_from_date', 'DESC')
			->limit($this->latest_items_limit)
			->get();
	}
	
	public function getForAdminList()
	{
		return \Neonbug\News\Models\News::all();
	}
	
}
