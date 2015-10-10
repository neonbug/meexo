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
	
	public function getTranslationsByIdTranslationSourceForAdmin($id_translation_source)
	{
		$model = $this->model;
		$items = $model::where('id_translation_source', $id_translation_source)->get();
		
		$values = [];
		foreach ($items as $item)
		{
			if (!array_key_exists($item->id_language, $values))
			{
				$values[$item->id_language] = [];
			}
			$values[$item->id_language]['translation'] = $item->value;
		}
		
		return $values;
	}
	
}
