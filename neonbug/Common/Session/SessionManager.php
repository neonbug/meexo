<?php namespace Neonbug\Common\Session;

use Illuminate\Support\Manager;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NullSessionHandler;

class SessionManager extends \Illuminate\Session\SessionManager {
	/**
	 * Build the session instance.
	 *
	 * @param  \SessionHandlerInterface  $handler
	 * @return \Illuminate\Session\Store
	 */
	protected function buildSession($handler)
	{
		if ($this->app['config']['session.encrypt'])
		{
			return new EncryptedStore(
				$this->app['config']['session.cookie'], $handler, $this->app['encrypter']
			);
		}
		else
		{
			return new Store($this->app['config']['session.cookie'], $handler);
		}
	}

}
