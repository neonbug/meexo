<?php
use Illuminate\Support\Str;

if ( ! function_exists('env'))
{
	/**
	 * Gets the value of an environment variable. Supports boolean, empty and null.
	 * 
	 * Note by TadejKan: this is here, because there is a bug, where a key exists in $_ENV, 
	 * but getenv function doesn't find it. So I added searching through $_ENV again (since 
	 * getenv should search there as well).
	 * Not sure, but maybe this bug only occures on Windows.
	 * To replicate the bug, do this:
	 * 1) comment out this fixed function
	 * 2) setup a POST (GET might work as well) route in routes.php
	 * 3) do a bunch of requests, some of those will fail (not the requests per-se, but 
	 *    stuff like connecting to the DB, since it won't have appropriate DB access info).
	 *    To generate these requests, you can do something like this:
	 *       for (var i=0; i<200; i++) $.post('your_route');
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	function env($key, $default = null)
	{
		$value = getenv($key);
		
		if ($value === false && array_key_exists($key, $_ENV)) $value = $_ENV[$key];
		if ($value === false) return value($default);

		switch (strtolower($value))
		{
			case 'true':
			case '(true)':
				return true;

			case 'false':
			case '(false)':
				return false;

			case 'empty':
			case '(empty)':
				return '';

			case 'null':
			case '(null)':
				return;
		}
		
		if (Str::startsWith($value, '"') && Str::endsWith($value, '"'))
		{
			return substr($value, 1, -1);
		}

		return $value;
	}
}
