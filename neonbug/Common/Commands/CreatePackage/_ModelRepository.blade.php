namespace {{ $namespace }}\{{ $package_name }}\Repositories;

class {{ $model_name }}Repository {
	
	const CONFIG_PREFIX = '{{ $config_root }}.{{ $config_prefix }}';
	
	protected $latest_items_limit = 20;
	protected $model;
	
	public function __construct()
	{
		$this->model = config(static::CONFIG_PREFIX . '.model');
	}
	
	public function getLatest()
	{
		$model = $this->model;
		return $model::orderBy('updated_at', 'DESC')
			->limit($this->latest_items_limit)
			->get();
	}
	
	public function getForAdminList()
	{
		$model = $this->model;
		return $model::all();
	}
	
}
