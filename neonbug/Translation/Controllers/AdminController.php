<?php namespace Neonbug\Translation\Controllers;

use App;
use Auth;
use Cache;
use Request;

class AdminController extends \Neonbug\Common\Http\Controllers\BaseAdminController {
	
	const PREFIX = 'translation';
	const CONFIG_PREFIX = 'neonbug.translation';
	
	private $model;
	private $model_source;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->model = config(static::CONFIG_PREFIX . '.model');
		$this->model_source = config(static::CONFIG_PREFIX . '.model_source');
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
		
		$model = $this->getModel();
		$translations = $model::all();
		$translations_map = [];
		foreach ($translations as $translation)
		{
			if (!array_key_exists($translation->id_translation_source, $translations_map))
			{
				$translations_map[$translation->id_translation_source] = [];
			}
			$translations_map[$translation->id_translation_source][$translation->id_language] = $translation->value;
		}
		
		$grouped_source_items = [
			'frontend' => [
				'title' => trans('translation::admin.list.type.frontend'), 
				'items' => []
			], 
			'admin'   => [
				'title' => trans('translation::admin.list.type.admin'), 
				'items' => []
			]
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
				$grouped_source_items[$type] = [
					'title' => trans('translation::admin.list.type.' . $type), 
					'items' => []
				];
			}
			if (!array_key_exists($package, $grouped_source_items[$type]['items']))
			{
				$grouped_source_items[$type]['items'][$package] = [
					'title' => trans($package . '::admin.title.main'), 
					'items' => []
				];
			}
			
			$grouped_source_items[$type]['items'][$package]['items'][$arr[1]] = [
				'id'           => $id, 
				'translations' => (!array_key_exists($id, $translations_map) ? [] : $translations_map[$id])
			];
		}
		
		$params = [
			'package_name' => $this->getPackageName(), 
			'title'        => $this->getListTitle(), 
			'items'        => $grouped_source_items, 
			'fields'       => config($this->getConfigPrefix() . '.list.fields'), 
			'edit_route'   => $this->getRoutePrefix() . '::admin::edit', 
			'delete_route' => $this->getRoutePrefix() . '::admin::delete', 
			'languages'    => App::make('LanguageRepository')->getAll()
		];
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(self::PREFIX, 'admin.list', $params);
	}
	
	public function adminEdit($id)
	{
		$model = $this->model_source;
		$item = $model::findOrFail($id);
		
		$languages = App::make('LanguageRepository')->getAll();
		
		$fields = $this->prepareFieldsForAddEdit(
			$languages, 
			config($this->getConfigPrefix() . '.edit.language_dependent_fields'), 
			config($this->getConfigPrefix() . '.edit.language_independent_fields'), 
			$item
		);
		
		$params = [
			'package_name'     => $this->getPackageName(), 
			'title'            => $this->getEditTitle(), 
			'fields'           => $fields, 
			'messages'         => session('messages', []), 
			'languages'        => $languages, 
			'check_slug_route' => null, 
			'prefix'           => $this->getRoutePrefix(), 
			'item'             => $item
		];
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')->loadView('translation', 'admin.add', $params);
	}
	
	public function adminEditPost($id)
	{
		$model = $this->model_source;
		$item = $model::findOrFail($id);
		
		return $this->adminEditPostHandle(
			false, 
			$item, 
			Request::input('field'), //first level keys are language ids, second level are field names
			(Request::file('field') == null ? [] : Request::file('field')), //first level keys are language ids, second level are field names
			Auth::user()->id_user, 
			config($this->getConfigPrefix() . '.add.language_independent_fields'), 
			config($this->getConfigPrefix() . '.add.language_dependent_fields'), 
			$this->getRoutePrefix()
		);
	}
	
	protected function adminEditPostHandle($is_preview, $item, $fields, $files, $id_user, $lang_independent_fields, 
		$lang_dependent_fields, $route_prefix)
	{
		$retval = $this->handleAdminAddEdit(
			$fields, 
			$files, 
			$id_user, 
			$lang_independent_fields, 
			$lang_dependent_fields, 
			$route_prefix, 
			$this->getModel(), 
			$item, 
			'edit'
		);
		
		Cache::forget($this->getPackageName() . '::item::' . $item->{$item->getKeyName()});
		Cache::forget($this->getPackageName() . '::items');
		
		return $retval;
	}
	
	protected function prepareFieldsForAddEdit($languages, $language_dependent_fields_config, 
		$language_independent_fields_config, $item = null)
	{
		$values = [];
		if ($item != null)
		{
			$repo = App::make($this->getRepository());
			$values = $repo->getTranslationsByIdTranslationSourceForAdmin($item->id_translation_source);
		}
		
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
	
	public function handleAdminAddEdit(Array $fields, Array $files, $id_user, 
		Array $language_independent_fields, Array $language_dependent_fields, $prefix, $model_name, $item, 
		$route_postfix)
	{
		$errors = []; //[ 'general' => 'DB error' ];
		
		$map = function($field) { return $field['name']; };
		$allowed_lang_independent_fields = array_map($map, $language_independent_fields);
		$allowed_lang_dependent_fields   = array_map($map, $language_dependent_fields);
		
		if ($route_postfix == 'add')
		{
			$event = new \Neonbug\Common\Events\AdminAddSavePreparedFields(
				$prefix, 
				$model_name, 
				$fields, 
				$allowed_lang_independent_fields, 
				$allowed_lang_dependent_fields
			);
			Event::fire($event);
			
			$fields                          = $event->fields;
			$allowed_lang_independent_fields = $event->language_independent_fields;
			$allowed_lang_dependent_fields   = $event->language_dependent_fields;
		}
		
		$this->fillAndSaveItem($item, $fields, $allowed_lang_independent_fields, $allowed_lang_dependent_fields);
		
		if (sizeof($errors) > 0)
		{
			return redirect(route($prefix . '::admin::' . $route_postfix, 
				($route_postfix == 'add' ? [] : [ $item->{$item->getKeyName()} ])))
				->withErrors($errors);
		}
		return redirect(route($prefix . '::admin::' . $route_postfix, 
			($route_postfix == 'add' ? [] : [ $item->{$item->getKeyName()} ])))
			->with([
				'messages' => [ trans('common::admin.main.messages.saved') ]
			]);
	}
	
	public function fillAndSaveItem($item, $fields, $allowed_language_independent_fields, 
		$allowed_language_dependent_fields)
	{
		$values = App::make('\Neonbug\Common\Helpers\AdminHelper')
			->fillItem($item, $fields, $allowed_language_independent_fields, $allowed_language_dependent_fields);
		
		$item->save();
		
		if (sizeof(array_keys($values)) > 0)
		{
			$this->updateTranslations($item->id_translation_source, $values);
		}
	}
	
	protected function updateTranslations($id_translation_source, $values)
	{
		$model = $this->getModel();
		$model_source = $this->model_source;
		
		// load existing translations
		$existing_translations = $model::where('id_translation_source', $id_translation_source)->get();
		$existing_trans_map = [];
		foreach ($existing_translations as $translation)
		{
			$existing_trans_map[$translation->id_language] = [
				'id_translation' => $translation->id_translation, 
				'value'          => $translation->value
			];
		}
		
		// process translations
		$translation_insert_arr = [];
		$translation_update_arr = [];
		foreach ($values as $id_language=>$value_item)
		{
			$value = $value_item['translation'];
			
			if (!array_key_exists($id_language, $existing_trans_map)) //new translation
			{
				$translation_insert_arr[] = [
					'id_translation_source' => $id_translation_source, 
					'id_language'           => $id_language, 
					'value'                 => $value, 
					'created_at'            => date('Y-m-d'), 
					'updated_at'            => date('Y-m-d')
				];
			}
			else if ($existing_trans_map[$id_language]['value'] != $value) //existing, but different
			{
				$translation_update_arr[$existing_trans_map[$id_language]['id_translation']] = $value;
			}
		}
		
		// insert new translations
		$model::insert($translation_insert_arr);
		
		// update existing translations
		foreach ($translation_update_arr as $id_translation=>$value)
		{
			$model::where('id_translation', $id_translation)
				->update([ 'value' => $value ]);
		}
	}
}
