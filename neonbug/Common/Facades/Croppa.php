<?php namespace Neonbug\Common\Facades;
class Croppa extends \Illuminate\Support\Facades\Facade {
	protected static function getFacadeAccessor() { return 'Neonbug\Common\Helpers\CroppaHelpers'; }
}
