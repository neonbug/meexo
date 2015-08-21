<?php

if ( ! function_exists('cached_asset'))
{
	/**
	 * Generate an asset path for the application.
	 *
	 * @param  string  $path
	 * @param  bool    $secure
	 * @return string
	 */
	function cached_asset($path, $secure = null)
	{
		return app('url')->cachedAsset($path, $secure);
	}
}
