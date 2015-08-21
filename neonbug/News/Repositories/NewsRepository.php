<?php namespace Neonbug\News\Repositories;

class NewsRepository {
	
	const CONFIG_PREFIX = 'neonbug.news';
	
	protected $latest_items_limit = 20;
	protected $model;
	
	public function __construct()
	{
		$this->model = config(static::CONFIG_PREFIX . '.model');
	}
	
	public function getLatest()
	{
		$model = $this->model;
		return $model::where('published', true)
			->where('published_from_date', '<=', date('Y-m-d'))
			->orderBy('published_from_date', 'DESC')
			->limit($this->latest_items_limit)
			->get();
	}
	
	public function getForAdminList()
	{
		$model = $this->model;
		return $model::all();
	}
	
}
