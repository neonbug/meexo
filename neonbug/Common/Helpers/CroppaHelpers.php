<?php namespace Neonbug\Common\Helpers;

/**
 * The public API to Croppa.  It generally passes through requests to other
 * classes
 */
class CroppaHelpers extends \Bkwld\Croppa\Helpers {

	/**
	 * Pass through URL requrests to URL->generate() using 'resize' as an option.
	 *
	 * @param string $url URL of an image that should be cropped
	 * @param integer $width Target width
	 * @param integer $height Target height
	 * @param array $options Addtional Croppa options, passed as key/value pairs.  Like array('resize')
	 * @return string The new path to your thumbnail
	 * @see Bkwld\Croppa\URL::generate()
	 */
	public function url_resize($url, $width = null, $height = null, $options = []) {
		return $this->url($url, $width, $height, array_merge(['resize'], $options));
	}

}