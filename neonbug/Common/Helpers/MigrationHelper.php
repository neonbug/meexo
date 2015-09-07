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
		// load translations from files
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
		
		// gather language codes
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
		
		// load existing translations
		$existing_translations = \Neonbug\Common\Models\Translation::all();
		$existing_source_keys = [];
		$existing_translation_keys = [];
		foreach ($existing_translations as $translation)
		{
			if (!in_array($translation->id_translation_source, $existing_source_keys))
			{
				$existing_source_keys[] = $translation->id_translation_source;
			}
			
			$key = $translation->id_language . '.' . $translation->id_translation_source;
			if (!array_key_exists($key, $existing_translation_keys))
			{
				$existing_translation_keys[$key] = [ 
					'id_translation' => $translation->id_translation, 
					'value'          => $translation->value
				];
			}
		}
		
		// insert translations
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
				// skip existing translation sources
				if (in_array($key, $existing_source_keys)) continue;
				
				$translation_source_insert_arr[] = [
					'id_translation_source' => $key, 
					'created_at'            => date('Y-m-d'), 
					'updated_at'            => date('Y-m-d')
				];
			}
			
			\Neonbug\Common\Models\TranslationSource::insert($translation_source_insert_arr);
			
			$translation_insert_arr = [];
			$translation_update_arr = [];
			foreach ($translations as $key=>$values)
			{
				foreach ($values as $lang=>$value)
				{
					if (!array_key_exists($lang, $locale_to_id_languages)) continue;
					$id_language = $locale_to_id_languages[$lang];
					
					$translation_key = $id_language . '.' . $key;
					if (array_key_exists($translation_key, $existing_translation_keys))
					{
						// skip existing translations with equal values
						if ($existing_translation_keys[$translation_key]['value'] == $value) continue;
						
						// update existing translations with different values
						$translation_update_arr[$existing_translation_keys[$translation_key]['id_translation']] = $value;
					}
					else
					{
						$translation_insert_arr[] = [
							'id_translation_source' => $key, 
							'id_language'           => $id_language, 
							'value'                 => $value, 
							'created_at'            => date('Y-m-d'), 
							'updated_at'            => date('Y-m-d')
						];
					}
				}
			}
			
			// insert new translations
			\Neonbug\Common\Models\Translation::insert($translation_insert_arr);
			
			// update existing translations
			foreach ($translation_update_arr as $id_translation=>$value)
			{
				\Neonbug\Common\Models\Translation::where('id_translation', $id_translation)
					->update([ 'value' => $value ]);
			}
			
			//TODO implement deleting missing translations?
		}
	}
	
}
