<?php namespace Neonbug\Common\Traits;

use App;
use Event;

trait SlugTrait {
	
	protected static $booted = false;
	
	public static function bootSlugTrait()
	{
		if (self::$booted === true) return;
		self::$booted = true;
		
		Event::listen('Neonbug\\Common\\Events\\AdminAddSavePreparedFields', function($event) {
			$interfaces = class_implements($event->class_name);
			if (!array_key_exists('Neonbug\Common\Traits\SlugTraitInterface', $interfaces)) return;
			
			$class_name = $event->class_name;
			$generate_from = $class_name::getGenerateSlugFrom();
			
			$resource_repo = App::make('ResourceRepository');
			
			$duplicate_slug_postfixes = [ 
				date('Y'), date('m'), date('d'), mt_rand(1, 100000)
			]; //things to try to add when a duplicate is found
			
			$found = false;
			foreach ($event->fields as $id_language=>$fields)
			{
				if ($id_language == -1) continue;
				
				foreach ($fields as $field_name=>$field_value)
				{
					if ($field_name == $generate_from)
					{
						$slug = str_slug($field_value);
						$duplicate_idx = 0;
						while (true)
						{
							if (!$resource_repo->slugExists($event->route_prefix, $id_language, $slug)) break;
							
							if ($duplicate_idx >= sizeof($duplicate_slug_postfixes))
							{
								$slug = null;
								break;
							}
							
							$slug .= '-' . $duplicate_slug_postfixes[$duplicate_idx++];
						}
						
						if ($slug == null) break;
						
						$event->fields[$id_language]['slug'] = $slug;
						
						$found = true;
						break;
					}
				}
			}
			
			if ($found)
			{
				$event->language_dependent_fields[] = 'slug';
			}
		});
	}
	
}
