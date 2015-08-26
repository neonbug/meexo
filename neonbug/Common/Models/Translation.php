<?php namespace Neonbug\Common\Models;

class Translation extends BaseModel {

	public static function getByLocaleAndGroupAndNamespace($locale, $group, $namespace = null)
	{
		$search_val = ($namespace == null ? 'app' : $namespace) . '::' . $group . '.%';
		
		$items = self::whereHas('language', function($q) use ($locale, $search_val) { $q->where('locale', $locale); })
			->where('id_translation_source', 'LIKE', $search_val)
			->get();
		
		$arr = [];
		foreach ($items as $item)
		{
			$key = mb_substr($item->id_translation_source, mb_stripos($item->id_translation_source, '.') + 1);
			$arr[$key] = $item->value;
		}
		
		$structured = [];
		foreach ($arr as $key=>$value) {
			array_set($structured, $key, $value);
		}
		
		return $structured;
	}
	
	public function language()
	{
		return $this->belongsTo('\Neonbug\Common\Models\Language', 'id_language', 'id_language');
	}

}
