<?php namespace Neonbug\News\Controllers;

class AdminController extends \Neonbug\Common\Http\Controllers\BaseAdminController {
	
	const CONFIG_PREFIX = 'neonbug.news';
	private $model;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->model = config(static::CONFIG_PREFIX . '.model');
	}
	
	protected function getModel()        { return $this->model; }
	protected function getRepository()   { return '\Neonbug\News\Repositories\NewsRepository'; }
	protected function getConfigPrefix() { return self::CONFIG_PREFIX; }
	protected function getRoutePrefix()  { return 'news'; }
	protected function getPackageName()  { return 'news'; }
	protected function getListTitle()    { return [ 'News', 'List' ]; }
	protected function getAddTitle()     { return [ 'News', 'Add' ]; }
	protected function getEditTitle()    { return [ 'News', 'Edit' ]; }
	
}
