<?php namespace Neonbug\Common\Session;

class Store extends \Illuminate\Session\Store {

	/**
	 * {@inheritdoc}
	 */
	public function start()
	{
		$this->loadSession();

		return $this->started = true;
	}
	
	/**
	 * Get the CSRF token value.
	 *
	 * @return string
	 */
	public function token()
	{
		if ( ! $this->has('_token')) $this->regenerateToken();
		
		return $this->get('_token');
	}

}
