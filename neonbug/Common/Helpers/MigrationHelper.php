<?php namespace Neonbug\Common\Helpers;

class MigrationHelper {
	
	function loadView($package_name, $view_name, $params)
	{
		if (view()->exists($package_name . '::' . $view_name))
		{
			return view($package_name . '::' . $view_name, $params);
		}
		return view($view_name, $params);
	}
	
	function insertTranslations($dir)
	{
		$translations = [];
		if (file_exists($dir))
		{
			$arr = scandir($dir);
			foreach ($arr as $item)
			{
				if ($item == '.' || $item == '..') continue;
				if (mb_strlen($item) < 4 || mb_substr($item, -4) != '.php') continue;
				
				$translations = array_merge($translations, include($dir . $item));
			}
		}
		
		$languages = [];
		$translation_keys = [];
		foreach ($translations as $key=>$values)
		{
			$translation_keys[] = $key;
			foreach ($values as $lang=>$value)
			{
				if (!in_array($lang, $languages)) $languages[] = $lang;
			}
		}
		
		if (sizeof($languages) > 0)
		{
			$locale_to_id_languages = [];
			$trans = \Neonbug\Common\Models\Language::whereIn('locale', $languages)->get();
			foreach ($trans as $item)
			{
				$locale_to_id_languages[$item->locale] = $item->id_language;
			}
			
			$translation_source_insert_arr = [];
			foreach ($translation_keys as $key)
			{
				$translation_source_insert_arr[] = [
					'id_translation_source' => $key, 
					'created_at'            => date('Y-m-d'), 
					'updated_at'            => date('Y-m-d')
				];
			}
			
			\Neonbug\Common\Models\TranslationSource::insert($translation_source_insert_arr);
			
			$translation_insert_arr = [];
			foreach ($translations as $key=>$values)
			{
				foreach ($values as $lang=>$value)
				{
					if (!array_key_exists($lang, $locale_to_id_languages)) continue;
					$id_language = $locale_to_id_languages[$lang];
					
					$translation_insert_arr[] = [
						'id_translation_source' => $key, 
						'id_language'           => $id_language, 
						'value'                 => $value, 
						'created_at'            => date('Y-m-d'), 
						'updated_at'            => date('Y-m-d')
					];
				}
			}
			
			\Neonbug\Common\Models\Translation::insert($translation_insert_arr);
		}
	}
	
}
