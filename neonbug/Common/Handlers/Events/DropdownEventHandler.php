<?php namespace Neonbug\Common\Handlers\Events;

use App;

class DropdownEventHandler
{
	/**
	* Register the listeners for the subscriber.
	*
	* @param  Illuminate\Events\Dispatcher  $events
	* @return void
	*/
	public function subscribe($events)
	{
		$events->listen('Neonbug\\Common\\Events\\AdminAddEditPrepareField', function($event) {
			if ($event->field['type'] != 'dropdown') return;
			if (array_key_exists('values', $event->field)) return; //already has values
			
			if (!array_key_exists('from', $event->field) || 
				!array_key_exists('value_field', $event->field) || 
				!array_key_exists('title_field', $event->field)) return;
			
			$class = $event->field['from'];
			$items = $class::all();
			
			$language      = App::make('Language');
			$resource_repo = App::make('ResourceRepository');
			$resource_repo->inflateObjectsWithValues($items, $language->id_language);
			
			$value_field = $event->field['value_field'];
			$title_field = $event->field['title_field'];
			
			$values = [];
			foreach ($items as $item)
			{
				$values[$item->{$value_field}] = $item->{$title_field};
			}
			
			$event->field['values'] = $values;
			
			//TODO do sth with default value
		});
	}
}
