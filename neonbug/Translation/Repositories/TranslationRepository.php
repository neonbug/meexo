<?php namespace Neonbug\Translation\Repositories;

class TranslationRepository {
	
	const CONFIG_PREFIX = 'neonbug.translation';
	
	protected $latest_items_limit = 20;
	protected $model;
	protected $model_source;
	
	public function __construct()
	{
		$this->model = config(static::CONFIG_PREFIX . '.model');
		$this->model_source = config(static::CONFIG_PREFIX . '.model_source');
	}
	
	public function getForAdminList()
	{
		$model = $this->model_source;
		return $model::orderBy('id_translation_source')->get();
	}
	
}
