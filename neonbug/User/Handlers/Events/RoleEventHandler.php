<?php namespace Neonbug\User\Handlers\Events;

use App;

class RoleEventHandler
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
			if ($event->field['type'] != 'user_admin::add_fields.role') return;
			
			$event->field['values'] = [
				'gallery' => 'Gallery editor', 
				'news'    => 'News editor', 
				'text'    => 'Text editor', 
			];
		});
	}
}
