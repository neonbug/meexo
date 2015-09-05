<?php namespace Neonbug\Common\Translation;

class MockTranslationServiceProvider extends \Illuminate\Translation\TranslationServiceProvider {

	/**
	 * Register the translation line loader.
	 *
	 * @return void
	 */
	protected function registerLoader()
	{
		$this->app->singleton('translation.loader', function($app)
		{
			return new MockLoader();
		});
	}

}
