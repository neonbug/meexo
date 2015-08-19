namespace Neonbug\{{ $package_name }}\Controllers;

use App;
use Request;
use Auth;
use Cache;

use \Neonbug\{{ $package_name }}\Models\{{ $model_name }} as Model;

class AdminController extends \App\Http\Controllers\Controller {
	
	const PACKAGE_NAME  = '{{ $lowercase_package_name }}';
	const PREFIX        = '{{ $route_prefix }}';
	const CONFIG_PREFIX = 'neonbug.{{ $config_prefix }}';
	
	private $admin_helper;
	
	public function __construct()
	{
		$this->admin_helper  = App::make('\Neonbug\Common\Helpers\AdminHelper');
	}
	
	public function admin_list()
	{
		return $this->admin_helper->adminList(
			[ '{{ $package_name }}', 'List' ], 
			config(static::CONFIG_PREFIX . '.list.fields'), 
			static::PREFIX, 
			'\Neonbug\{{ $package_name }}\Repositories\{{ $model_name }}Repository'
		);
	}
	
	public function admin_add()
	{
		return $this->admin_helper->adminAdd(
			[ '{{ $package_name }}', 'Add' ], 
			config(static::CONFIG_PREFIX . '.add.language_dependent_fields'), 
			config(static::CONFIG_PREFIX . '.add.language_independent_fields'), 
			session('messages', [])
		);
	}
	
	public function admin_add_post()
	{
		$retval = $this->admin_helper->handleAdminAdd(
			Request::input('field'), //first level keys are language ids, second level are field names
			'\Neonbug\{{ $package_name }}\Models\{{ $model_name }}', 
			Auth::user()->id_user, 
			config(static::CONFIG_PREFIX . '.add.language_independent_fields'), 
			config(static::CONFIG_PREFIX . '.add.language_dependent_fields'), 
			static::PREFIX
		);
		
		Cache::forget(static::PACKAGE_NAME . '::items');
		
		return $retval;
	}
	
	public function admin_edit($id)
	{
		$item = Model::findOrFail($id);
		
		return $this->admin_helper->adminEdit(
			[ '{{ $package_name }}', 'Edit' ], 
			config(static::CONFIG_PREFIX . '.edit.language_dependent_fields'), 
			config(static::CONFIG_PREFIX . '.edit.language_independent_fields'), 
			session('messages', []), 
			$item
		);
	}
	
	public function admin_edit_post($id)
	{
		$item = Model::findOrFail($id);
		
		$retval = $this->admin_helper->handleAdminEdit(
			Request::input('field'), //first level keys are language ids, second level are field names
			'\Neonbug\{{ $package_name }}\Models\{{ $model_name }}', 
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
	
	public function admin_delete_post()
	{
		$id   = Request::input('id');
		$item = Model::findOrFail($id);
		
		$this->admin_helper->deleteItem($id, '\Neonbug\{{ $package_name }}\Models\{{ $model_name }}', 
			$item->{$item->getKeyName()});
			
		Cache::forget(static::PACKAGE_NAME . '::item::' . $item->{$item->getKeyName()});
		Cache::forget(static::PACKAGE_NAME . '::items');
		
		return [ 'success' => true ];
	}
	
}
