<?php namespace Neonbug\Common\Traits;

use App;
use Event;
use Hash;

trait PasswordTrait {
	
	protected static $booted = false;
	
	public static function bootPasswordTrait()
	{
		if (self::$booted === true) return;
		self::$booted = true;
		
		Event::listen('Neonbug\\Common\\Events\\AdminAddSavePreparedFields', function($event) {
			self::handlePreparedFieldsEvent($event);
		});
		
		Event::listen('Neonbug\\Common\\Events\\AdminEditSavePreparedFields', function($event) {
			self::handlePreparedFieldsEvent($event);
		});
		
		Event::listen('Neonbug\\Common\\Events\\AdminEditPreparedFields', function($event) {
			$interfaces = class_implements($event->class_name);
			if (!array_key_exists('Neonbug\Common\Traits\PasswordTraitInterface', $interfaces)) return;
			
			$class_name = $event->class_name;
			$password_fields = $class_name::getPasswordFields();
			
			if (sizeof($password_fields) > 0)
			{
				foreach ($event->fields as $type=>$fields)
				{
					foreach ($fields as $idx=>$field)
					{
						$field_name = $field['name'];
						if (!in_array($field_name, $password_fields)) continue;
						
						$event->fields[$type][$idx]['value'] = '';
					}
				}
			}
		});
	}
	
	protected static function handlePreparedFieldsEvent($event)
	{
		$interfaces = class_implements($event->class_name);
		if (!array_key_exists('Neonbug\Common\Traits\PasswordTraitInterface', $interfaces)) return;
		
		$class_name = $event->class_name;
		$password_fields = $class_name::getPasswordFields();
		
		if (sizeof($password_fields) > 0)
		{
			foreach ($event->fields as $id_language=>$fields)
			{
				foreach ($fields as $field_name=>$field_value)
				{
					if (!in_array($field_name, $password_fields)) continue;
					
					if ($field_value == '')
					{
						unset($event->fields[$id_language][$field_name]);
					}
					else
					{
						$event->fields[$id_language][$field_name] = Hash::make($field_value);
					}
				}
			}
		}
	}
	
}
