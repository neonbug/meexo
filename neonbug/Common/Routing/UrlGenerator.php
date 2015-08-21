<?php namespace Neonbug\Common\Routing;

use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Routing\UrlGenerator as UrlGeneratorContract;

class UrlGenerator extends \Illuminate\Routing\UrlGenerator {

	public function cachedAsset($path, $secure = null)
	{
		if ($this->isValidUrl($path)) return $path;

		// Once we get the root URL, we will check to see if it contains an index.php
		// file in the paths. If it does, we will remove it since it is not needed
		// for asset paths, but only for routes to endpoints in the application.
		$root = $this->getRootUrl($this->getScheme($secure));

		$parts = pathinfo($path);
		$cached_path = implode('/', [
			'cached', 
			filemtime($path), 
			trim($parts['dirname'], '/'), 
			$parts['basename']
		]);
		
		return $this->removeIndex($root).'/'.$cached_path;
	}

}
