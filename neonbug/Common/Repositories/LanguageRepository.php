<?php namespace Neonbug\Common\Repositories;

class LanguageRepository {
	
	public function getAll()
	{
		return \Neonbug\Common\Models\Language::all();
	}
	
}
