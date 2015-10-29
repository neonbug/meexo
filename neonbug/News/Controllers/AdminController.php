<?php namespace Neonbug\News\Controllers;

use Request;
use Auth;

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
	
	public function adminAddPost()
	{
		$is_preview = (Request::input('preview') !== null);
		
		$model = $this->getModel();
		$item = new $model();
		$item->id_user = Auth::user()->id_user;
		
		return $this->adminAddPostHandle(
			$is_preview, 
			$item, 
			Request::input('field'), //first level keys are language ids, second level are field names
			(Request::file('field') == null ? [] : Request::file('field')), //first level keys are language ids, second level are field names
			Auth::user()->id_user, 
			config($this->getConfigPrefix() . '.add.language_independent_fields'), 
			config($this->getConfigPrefix() . '.add.language_dependent_fields'), 
			$this->getRoutePrefix()
		);
	}
	
}
