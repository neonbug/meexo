<?php namespace Neonbug\Text\Repositories;

class TextRepository {
	
	const CONFIG_PREFIX = 'neonbug.text';
	
	protected $latest_items_limit = 20;
	protected $model;
	
	public function __construct()
	{
		$this->model = config(static::CONFIG_PREFIX . '.model');
	}
	
	public function getLatest()
	{
		$model = $this->model;
		return $model::orderBy('updated_at', 'DESC')
			->limit($this->latest_items_limit)
			->get();
	}
	
	public function getForAdminList()
	{
		$model = $this->model;
		return $model::all();
	}
	
}
