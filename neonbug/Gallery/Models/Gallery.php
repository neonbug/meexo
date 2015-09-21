<?php namespace Neonbug\Gallery\Models;

class Gallery extends \Neonbug\Common\Models\BaseModel implements \Neonbug\Common\Traits\OrdTraitInterface {
	
	public $gallery_images = []; // keys are id languages, then field names
	
	public static function getOrdFields() { return [ 'ord' ]; } // from OrdTraitInterface
	
}
