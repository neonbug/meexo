<?php namespace Neonbug\News\Controllers;

use App;
use Request;
use Auth;
use Cache;

class AdminController extends \App\Http\Controllers\Controller {
	
	const PACKAGE_NAME  = 'news';
	const PREFIX        = 'news';
	const CONFIG_PREFIX = 'neonbug.news';
	
	private $admin_helper;
	private $model;
	
	public function __construct()
	{
		$this->admin_helper = App::make('\Neonbug\Common\Helpers\AdminHelper');
		$this->model = config(static::CONFIG_PREFIX . '.model');
	}
	
	public function admin_list()
	{
		return $this->admin_helper->adminList(
			[ 'News', 'List' ], 
			config(static::CONFIG_PREFIX . '.list.fields'), 
			static::PREFIX, 
			'\Neonbug\News\Repositories\NewsRepository'
		);
	}
	
	public function admin_add()
	{
		return $this->admin_helper->adminAdd(
			[ 'News', 'Add' ], 
			config(static::CONFIG_PREFIX . '.add.language_dependent_fields'), 
			config(static::CONFIG_PREFIX . '.add.language_independent_fields'), 
			session('messages', [])
		);
	}
	
	public function admin_add_post()
	{
		if (Request::input('preview') !== null)
		{
			return $this->admin_add_preview_post();
		}
		
		$retval = $this->admin_helper->handleAdminAdd(
			Request::input('field'), //first level keys are language ids, second level are field names
			$model, 
			Auth::user()->id_user, 
			config(static::CONFIG_PREFIX . '.add.language_independent_fields'), 
			config(static::CONFIG_PREFIX . '.add.language_dependent_fields'), 
			static::PREFIX
		);
		
		Cache::forget(static::PACKAGE_NAME . '::items');
		
		return $retval;
	}
	
	private function admin_add_preview_post()
	{
		$retval = $this->admin_helper->handleAdminPreview(
			Request::input('field'), //first level keys are language ids, second level are field names
			Auth::user()->id_user, 
			config(static::CONFIG_PREFIX . '.add.language_independent_fields'), 
			config(static::CONFIG_PREFIX . '.add.language_dependent_fields'), 
			static::PREFIX
		);
		
		return $retval;
	}
	
	public function admin_edit($id)
	{
		$model = $this->model;
		$item = $model::findOrFail($id);
		
		return $this->admin_helper->adminEdit(
			[ 'News', 'Edit' ], 
			config(static::CONFIG_PREFIX . '.edit.language_dependent_fields'), 
			config(static::CONFIG_PREFIX . '.edit.language_independent_fields'), 
			session('messages', []), 
			$item
		);
	}
	
	public function admin_edit_post($id)
	{
		if (Request::input('preview') !== null)
		{
			return $this->admin_edit_preview_post($id);
		}
		
		$model = $this->model;
		$item = $model::findOrFail($id);
		
		$retval = $this->admin_helper->handleAdminEdit(
			Request::input('field'), //first level keys are language ids, second level are field names
			$model, 
			Auth::user()->id_user, 
			config(static::CONFIG_PREFIX . '.edit.language_independent_fields'), 
			config(static::CONFIG_PREFIX . '.edit.language_dependent_fields'), 
			static::PREFIX, 
			$item
		);
		
		Cache::forget(static::PACKAGE_NAME . '::item::' . $item->{$item->getKeyName()});
		Cache::forget(static::PACKAGE_NAME . '::items');
		
		return $retval;
	}
	
	private function admin_edit_preview_post($id)
	{
		$model = $this->model;
		$item = $model::findOrFail($id);
		
		$retval = $this->admin_helper->handleAdminPreview(
			Request::input('field'), //first level keys are language ids, second level are field names
			Auth::user()->id_user, 
			config(static::CONFIG_PREFIX . '.add.language_independent_fields'), 
			config(static::CONFIG_PREFIX . '.add.language_dependent_fields'), 
			static::PREFIX, 
			$id
		);
		
		return $retval;
	}
	
	public function admin_delete_post()
	{
		$model = $this->model;
		
		$id   = Request::input('id');
		$item = $model::findOrFail($id);
		
		$this->admin_helper->deleteItem($id, $model, $item->getKeyName());
		
		Cache::forget(static::PACKAGE_NAME . '::item::' . $item->{$item->getKeyName()});
		Cache::forget(static::PACKAGE_NAME . '::items');
		
		return [ 'success' => true ];
	}
	
}
