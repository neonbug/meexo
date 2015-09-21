<?php namespace Neonbug\Common\Traits;

use Event;

trait OrdTrait {
	
	protected static $booted = false;
	
	public static function bootOrdTrait()
	{
		if (self::$booted === true) return;
		self::$booted = true;
		
		Event::listen('Neonbug\\Common\\Events\\AdminAddPreparedFields', function($event) {
			$interfaces = class_implements($event->class_name);
			if (!array_key_exists('Neonbug\Common\Traits\OrdTraitInterface', $interfaces)) return;
			
			$class_name = $event->class_name;
			foreach ($class_name::getOrdFields() as $field_name)
			{
				$new_value = $class_name::all()->max($field_name);
				$new_value = ($new_value === null ? 1 : intval($new_value) + 10);
				
				foreach ($event->fields['language_independent'] as $idx=>$field)
				{
					if ($field['name'] != $field_name) continue;
					
					$event->fields['language_independent'][$idx]['value'] = $new_value;
				}
			}
		});
	}
	
}
