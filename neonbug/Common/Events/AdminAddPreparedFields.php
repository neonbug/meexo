<?php namespace Neonbug\Common\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class AdminAddPreparedFields extends Event {

	use SerializesModels;
	
	public $class_name;
	public $fields;
	
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($class_name, $fields)
	{
		$this->class_name = $class_name;
		$this->fields = $fields;
	}
	
}
