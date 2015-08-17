<?php namespace Neonbug\Common\Helpers;

class CommonHelper {
	
	function loadView($package_name, $view_name, $params)
	{
		if (view()->exists($package_name . '::' . $view_name))
		{
			return view($package_name . '::' . $view_name, $params);
		}
		return view($view_name, $params);
	}
	
}
