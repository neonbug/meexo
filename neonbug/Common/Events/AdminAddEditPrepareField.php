<?php namespace Neonbug\Common\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class AdminAddEditPrepareField extends Event {

	use SerializesModels;
	
	public $type;
	public $field;
	
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($type, $field)
	{
		$this->type = $type;
		$this->field = $field;
	}
	
}
