<?php namespace Neonbug\Common\Helpers;

use App;
use Cache;

//TODO this class isn't very pretty; refactor!
class AdminHelper {
	
	public function getListItems($repo_class)
	{
		$language      = App::make('Language');
		$resource_repo = App::make('ResourceRepository');
		$item_repo     = App::make($repo_class);
		
		$items = $item_repo->getForAdminList();
		$resource_repo->inflateObjectsWithValues($items, $language->id_language);
		
		return $items;
	}
	
	public function prepareFieldsForAdd($languages, $language_dependent_fields_config, 
		$language_independent_fields_config)
	{
		return $this->prepareFieldsForAddEdit($languages, $language_dependent_fields_config, 
			$language_independent_fields_config);
	}
	
	public function prepareFieldsForEdit($languages, $language_dependent_fields_config, 
		$language_independent_fields_config, $item)
	{
		return $this->prepareFieldsForAddEdit($languages, $language_dependent_fields_config, 
			$language_independent_fields_config, $item);
	}
	
	private function prepareFieldsForAddEdit($languages, $language_dependent_fields_config, 
		$language_independent_fields_config, $item = null)
	{
		$values = ($item == null ? [] : 
			App::make('ResourceRepository')->getValues($item->getTableName(), $item->{$item->getKeyName()}));
		
		$lang_dependent_fields = [];
		$lang_dependent_fields_config = $language_dependent_fields_config;
		if (sizeof($lang_dependent_fields_config) > 0)
		{
			foreach ($languages as $language)
			{
				$id_language = $language->id_language;
				$lang_dependent_fields[$id_language] = $lang_dependent_fields_config;
				
				for ($i=0; $i<sizeof($lang_dependent_fields[$id_language]); $i++)
				{
					$field = $lang_dependent_fields[$id_language][$i];
					
					if (array_key_exists($id_language, $values) && 
						array_key_exists($field['name'], $values[$id_language]))
					{
						$lang_dependent_fields[$id_language][$i]['value'] = $values[$id_language][$field['name']];
					}
				}
			}
		}
		
		$fields = [
			'language_independent' => $language_independent_fields_config, 
			'language_dependent'   => $lang_dependent_fields
		];
		
		if ($item != null)
		{
			for ($i=0; $i<sizeof($fields['language_independent']); $i++)
			{
				$fields['language_independent'][$i]['value'] = $item->{$fields['language_independent'][$i]['name']};
			}
		}
		
		return $fields;
	}
	
	public function fillAndSaveItem($item, $fields, $allowed_language_independent_fields, 
		$allowed_language_dependent_fields)
	{
		$values = $this->fillItem($item, $fields, $allowed_language_independent_fields, 
			$allowed_language_dependent_fields);
		
		$item->save();
		
		if (sizeof(array_keys($values)) > 0)
		{
			$resource_repo = App::make('ResourceRepository');
			$resource_repo->setValues($item->getTableName(), $item->{$item->getKeyName()}, $values);
		}
	}
	
	public function fillItem($item, $fields, $allowed_language_independent_fields, 
		$allowed_language_dependent_fields)
	{
		$values = []; //language depedent values
		foreach ($fields as $id_language=>$field)
		{
			if ($id_language == -1) //general fields
			{
				foreach ($field as $field_name=>$field_value)
				{
					if (!in_array($field_name, $allowed_language_independent_fields)) continue;
					$item->$field_name = $field_value;
				}
			}
			else
			{
				if (!array_key_exists($id_language, $values))
				{
					$values[$id_language] = [];
				}
				foreach ($field as $field_name=>$field_value)
				{
					if (!in_array($field_name, $allowed_language_dependent_fields)) continue;
					$values[$id_language][$field_name] = $field_value;
				}
			}
		}
		
		return $values;
	}
	
	public function deleteItem($id, $model, $primary_key)
	{
		$model::where($primary_key, $id)
			->delete();
		
		App::make('ResourceRepository')
			->deleteValues(call_user_func($model . '::getTableName'), [ $id ]);
	}
	
	//rendering
	public function adminList($package_name, Array $title, Array $fields, $prefix, $repo_class)
	{
		$items = App::make('\Neonbug\Common\Helpers\AdminHelper')
			->getListItems($repo_class);
		
		$params = [
			'package_name' => $package_name, 
			'title'        => $title, 
			'items'        => $items, 
			'fields'       => $fields, 
			'edit_route'   => $prefix . '::admin::edit', 
			'delete_route' => $prefix . '::admin::delete'
		];
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView('common', 'admin.list', $params);
	}
	
	public function adminAdd($package_name, Array $title, Array $language_dependent_fields, Array $language_independent_fields, 
		Array $messages)
	{
		$languages = App::make('LanguageRepository')->getAll();
		
		$fields = App::make('\Neonbug\Common\Helpers\AdminHelper')->prepareFieldsForAdd(
			$languages, 
			$language_dependent_fields, 
			$language_independent_fields
		);
		
		$params = [
			'package_name' => $package_name, 
			'title'        => $title, 
			'fields'       => $fields, 
			'messages'     => $messages, 
			'languages'    => $languages
		];
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')->loadView('common', 'admin.add', $params);
	}
	
	public function adminEdit($package_name, Array $title, Array $language_dependent_fields, Array $language_independent_fields, 
		Array $messages, $item)
	{
		$languages = App::make('LanguageRepository')->getAll();
		
		$fields = App::make('\Neonbug\Common\Helpers\AdminHelper')->prepareFieldsForEdit(
			$languages, 
			$language_dependent_fields, 
			$language_independent_fields, 
			$item
		);
		
		$params = [
			'package_name' => $package_name, 
			'title'        => $title, 
			'fields'       => $fields, 
			'messages'     => $messages, 
			'languages'    => $languages
		];
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')->loadView('common', 'admin.add', $params);
	}
	
	public function handleAdminAdd(Array $fields, $model_class, $id_user, Array $language_independent_fields, 
		Array $language_dependent_fields, $prefix)
	{
		$errors = []; //[ 'general' => 'DB error' ];
		
		$item = new $model_class();
		$item->id_user = $id_user;
		
		$map = function($field) { return $field['name']; };
		$allowed_lang_independent_fields = array_map($map, $language_independent_fields);
		$allowed_lang_dependent_fields   = array_map($map, $language_dependent_fields);
		
		App::make('\Neonbug\Common\Helpers\AdminHelper')
			->fillAndSaveItem($item, $fields, $allowed_lang_independent_fields, $allowed_lang_dependent_fields);
		
		if (sizeof($errors) > 0)
		{
			return redirect(route($prefix . '::admin::add'))
				->withErrors($errors);
		}
		return redirect(route($prefix . '::admin::add'))
			->with([
				'messages' => [ 'Saved' ]
			]);
	}
	
	public function handleAdminPreview(Array $fields, $id_user, Array $language_independent_fields, 
		Array $language_dependent_fields, $prefix, $id_item = -1)
	{
		$errors = []; //[ 'general' => 'DB error' ];
		
		$map = function($field) { return $field['name']; };
		$allowed_lang_independent_fields = array_map($map, $language_independent_fields);
		$allowed_lang_dependent_fields   = array_map($map, $language_dependent_fields);
		
		$key = str_random(10);
		Cache::remember($prefix . '::admin::preview::' . $key, 10, function() use ($fields, $id_user, $id_item, 
			$allowed_lang_independent_fields, $allowed_lang_dependent_fields) { 
			return [
				'id_item'                         => $id_item, 
				'fields'                          => $fields, 
				'id_user'                         => $id_user, 
				'allowed_lang_independent_fields' => $allowed_lang_independent_fields, 
				'allowed_lang_dependent_fields'   => $allowed_lang_dependent_fields
			];
		});
		
		if (sizeof($errors) > 0)
		{
			return redirect(route($prefix . '::preview', [ $key ]))
				->withErrors($errors);
		}
		return redirect(route($prefix . '::preview', [ $key ]))
			->with([
				'messages' => [ 'Saved' ]
			]);
	}
	
	public function handleAdminEdit(Array $fields, $model_class, $id_user, Array $language_independent_fields, 
		Array $language_dependent_fields, $prefix, $item)
	{
		$errors = []; //[ 'general' => 'DB error' ];
		
		$map = function($field) { return $field['name']; };
		$allowed_lang_independent_fields = array_map($map, $language_independent_fields);
		$allowed_lang_dependent_fields   = array_map($map, $language_dependent_fields);
		
		App::make('\Neonbug\Common\Helpers\AdminHelper')
			->fillAndSaveItem($item, $fields, $allowed_lang_independent_fields, $allowed_lang_dependent_fields);
		
		if (sizeof($errors) > 0)
		{
			return redirect(route($prefix . '::admin::edit', [ $item->{$item->getKeyName()} ]))
				->withErrors($errors);
		}
		return redirect(route($prefix . '::admin::edit', [ $item->{$item->getKeyName()} ]))
			->with([
				'messages' => [ 'Saved' ]
			]);
	}
}
