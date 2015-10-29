<?php namespace Neonbug\User\Controllers;

use Auth;
use Request;
use \Neonbug\Common\Models\UserRole as UserRole;

class AdminController extends \Neonbug\Common\Http\Controllers\BaseAdminController {
	
	const CONFIG_PREFIX = 'neonbug.user';
	private $model;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->model = config(static::CONFIG_PREFIX . '.model');
	}
	
	protected function getModel()        { return $this->model; }
	protected function getRepository()   { return '\Neonbug\User\Repositories\UserRepository'; }
	protected function getConfigPrefix() { return self::CONFIG_PREFIX; }
	protected function getRoutePrefix()  { return 'user'; }
	protected function getPackageName()  { return 'user'; }
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
		
		try
		{
			$retval = $this->adminAddPostHandle(
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
		catch (\Illuminate\Database\QueryException $ex)
		{
			if (sizeof($ex->errorInfo) == 3 && 
				mb_stripos($ex->errorInfo[2], 'duplicate key value violates unique constraint') !== false && 
				mb_stripos($ex->errorInfo[2], 'user_username_unique') !== false)
			{
				return redirect(route($this->getRoutePrefix() . '::admin::add', []))
					->withErrors([ 'general' => trans('user::admin.add.errors.duplicate-username') ]);
			}
			
			throw $ex;
		}
		
		$id_item = $item->{$item->getKeyName()};
		
		$this->processRoles(
			[], //no roles yet, since this is a new item
			(Request::input('admin_role') == null ? [] : Request::input('admin_role')), //first level keys are language ids, second level are field names
			(Request::input('role') == null ? [] : Request::input('role')), //first level keys are language ids, second level are field names
			$id_item
		);
		
		return $retval;
	}
	
	protected function processRoles($current_roles, $admin_roles, $roles, $id_item)
	{
		$role_name_to_id_user_role = [];
		$current_role_names = [];
		$new_role_names = [];
		$delete_id_user_roles = [];
		foreach ($current_roles as $role)
		{
			$role_name_to_id_user_role[$role->id_role] = $role->id_user_role;
			$current_role_names[] = $role->id_role;
		}
		
		$is_admin = false;
		foreach ($admin_roles as $id_language=>$fields)
		{
			foreach ($fields as $field_name=>$value)
			{
				if ($value == 'true')
				{
					$is_admin = true;
					break 2;
				}
			}
		}
		
		if ($is_admin)
		{
			if (!array_key_exists('admin', $role_name_to_id_user_role))
			{
				$new_role_names[] = 'admin';
			}
		}
		else
		{
			foreach ($roles as $id_language=>$fields)
			{
				foreach ($fields as $field_name=>$items)
				{
					if (!is_array($items)) continue;
					
					foreach ($items as $role)
					{
						if (array_key_exists($role, $role_name_to_id_user_role))
						{
							$delete_id_user_roles[] = $role_name_to_id_user_role[$role];
							continue;
						}
						
						$new_role_names[] = $role;
					}
				}
			}
		}
		
		// delete uneeded roles
		UserRole::whereIn('id_user_role', $delete_id_user_roles)->delete();
		
		// add new roles
		foreach ($new_role_names as $role)
		{
			$item = new UserRole();
			$item->id_user = $id_item;
			$item->id_role = $role;
			$item->save();
		}
	}
}
