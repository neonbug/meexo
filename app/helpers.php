<?php

if ( ! function_exists('resize_contents_images'))
{
	/**
	 * Replace srcs of images with relative paths with Croppa URL to prevent large images from being displayed.
	 *
	 * @param  string  $contents
	 * @param  int     $width
	 * @return string
	 */
	function resize_contents_images($contents, $width)
	{
		return preg_replace_callback(
			"/(\"|')(\/uploads\/images\/.*)(\"|')/iU", 
			function ($matches) use ($width) {
				if (mb_strlen($matches[2]) == 0 || mb_substr($matches[2], 0, 1) != '/')
				{
					return $matches[0];
				}
				return $matches[1] . 
					Croppa::url_resize($matches[2], $width) . 
					$matches[3];
			}, 
			$contents
		);
	}
}

if ( ! function_exists('replace_youtube_nocookie'))
{
	function replace_youtube_nocookie($contents)
	{
		return preg_replace_callback(
			"/(\"|')(https:\/\/)(.*youtube\\.com)(.*)(\"|')/iU", 
			function ($matches) {
				if (sizeof($matches) != 6 || mb_strlen($matches[3]) == 0)
				{
					return $matches[0];
				}
				return $matches[1] . $matches[2] . 
					'www.youtube-nocookie.com' . 
					$matches[4] . $matches[5];
			}, 
			$contents
		);
	}
}
