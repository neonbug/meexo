<?php namespace Neonbug\Common\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class AdminAddSavePreparedFields extends Event {

	use SerializesModels;
	
	public $route_prefix;
	public $class_name;
	public $fields;
	public $language_independent_fields;
	public $language_dependent_fields;
	
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($route_prefix, $class_name, $fields, $language_independent_fields, $language_dependent_fields)
	{
		$this->route_prefix                = $route_prefix;
		$this->class_name                  = $class_name;
		$this->fields                      = $fields;
		$this->language_independent_fields = $language_independent_fields;
		$this->language_dependent_fields   = $language_dependent_fields;
	}
	
}
