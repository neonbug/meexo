namespace Neonbug\{{ $package_name }}\Repositories;

class {{ $model_name }}Repository {
	
	protected $latest_items_limit = 20;
	
	public function getLatest()
	{
		return \Neonbug\{{ $package_name }}\Models\{{ $model_name }}::orderBy('updated_at', 'DESC')
			->limit($this->latest_items_limit)
			->get();
	}
	
	public function getForAdminList()
	{
		return \Neonbug\{{ $package_name }}\Models\{{ $model_name }}::all();
	}
	
}
