<?php namespace Neonbug\Translation\Controllers;

use App;

class AdminController extends \Neonbug\Common\Http\Controllers\BaseAdminController {
	
	const PREFIX = 'translation';
	const CONFIG_PREFIX = 'neonbug.translation';
	private $model;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->model = config(static::CONFIG_PREFIX . '.model');
	}
	
	protected function getModel()        { return $this->model; }
	protected function getRepository()   { return '\Neonbug\Translation\Repositories\TranslationRepository'; }
	protected function getConfigPrefix() { return self::CONFIG_PREFIX; }
	protected function getRoutePrefix()  { return 'translation'; }
	protected function getPackageName()  { return 'translation'; }
	protected function getListTitle()    { return [ 
		trans($this->getPackageName() . '::admin.title.main'), 
		trans($this->getPackageName() . '::admin.title.list')
	]; }
	protected function getAddTitle()     { return [ 
		trans($this->getPackageName() . '::admin.title.main'), 
		trans($this->getPackageName() . '::admin.title.add')
	]; }
	protected function getEditTitle()    { return [ 
		trans($this->getPackageName() . '::admin.title.main'), 
		trans($this->getPackageName() . '::admin.title.edit')
	]; }
	
	public function adminList()
	{
		$repo = App::make($this->getRepository());
		$source_items = $repo->getForAdminList();
		
		$grouped_source_items = [
			'frontend' => [], 
			'admin'   => []
		];
		
		foreach ($source_items as $item)
		{
			$id = $item->id_translation_source;
			
			$arr = explode('::', $id);
			if (sizeof($arr) != 2) continue;
			
			$package = $arr[0];
			$arr = explode('.', $arr[1], 2);
			
			if (sizeof($arr) < 2) continue;
			
			$type = $arr[0]; //should be admin or frontend
			if (!array_key_exists($type, $grouped_source_items))
			{
				$grouped_source_items[$type] = [];
			}
			if (!array_key_exists($package, $grouped_source_items[$type]))
			{
				$grouped_source_items[$type][$package] = [];
			}
			
			$grouped_source_items[$type][$package][$arr[1]] = $id;
		}
		
		$params = [
			'package_name' => $this->getPackageName(), 
			'title'        => $this->getListTitle(), 
			'items'        => $grouped_source_items, 
			'fields'       => config($this->getConfigPrefix() . '.list.fields'), 
			'edit_route'   => $this->getRoutePrefix() . '::admin::edit', 
			'delete_route' => $this->getRoutePrefix() . '::admin::delete'
		];
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(self::PREFIX, 'admin.list', $params);
	}
}
