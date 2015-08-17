<?php namespace Neonbug\News\Controllers;

use App;
use Request;
use Auth;

use \Neonbug\News\Models\News as News;

class AdminController extends \App\Http\Controllers\Controller {
	
	const PACKAGE_NAME 	= 'news';
	const PREFIX 		= 'news';
	const CONFIG_PREFIX = 'neonbug.news';
	
	private $admin_helper;
	
	public function __construct()
	{
		$this->admin_helper  = App::make('\Neonbug\Common\Helpers\AdminHelper');
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
		return $this->admin_helper->handleAdminAdd(
			Request::input('field'), //first level keys are language ids, second level are field names
			'\Neonbug\News\Models\News', 
			Auth::user()->id_user, 
			config(static::CONFIG_PREFIX . '.add.language_independent_fields'), 
			config(static::CONFIG_PREFIX . '.add.language_dependent_fields'), 
			static::PREFIX
		);
	}
	
	public function admin_edit($id)
	{
		$item = News::findOrFail($id);
		
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
		$item = News::findOrFail($id);
		
		return $this->admin_helper->handleAdminEdit(
			Request::input('field'), //first level keys are language ids, second level are field names
			'\Neonbug\News\Models\News', 
			Auth::user()->id_user, 
			config(static::CONFIG_PREFIX . '.edit.language_independent_fields'), 
			config(static::CONFIG_PREFIX . '.edit.language_dependent_fields'), 
			static::PREFIX, 
			$item
		);
	}
	
	public function admin_delete_post()
	{
		$id   = Request::input('id');
		$item = News::findOrFail($id);
		
		$this->admin_helper->deleteItem($id, '\Neonbug\News\Models\News', 'id_news');
		
		return [ 'success' => true ];
	}
	
}
