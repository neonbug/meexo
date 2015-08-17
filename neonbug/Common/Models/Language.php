<?php namespace Neonbug\Common\Models;

class Language extends BaseModel {

	public static function getByLocale($locale)
	{
		$arr = self::where('locale', $locale)->get();
		return (sizeof($arr) == 0 ? null : $arr[0]);
	}

}
