namespace Neonbug\{{ $package_name }}\Controllers;

class AdminController extends \Neonbug\Common\Http\Controllers\BaseAdminController {
	
	const CONFIG_PREFIX = 'neonbug.{{ $config_prefix }}';
	private $model;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->model = config(static::CONFIG_PREFIX . '.model');
	}
	
	protected function getModel()        { return $this->model; }
	protected function getRepository()   { return '\Neonbug\{{ $package_name }}\Repositories\{{ $model_name }}Repository'; }
	protected function getConfigPrefix() { return self::CONFIG_PREFIX; }
	protected function getRoutePrefix()  { return '{{ $route_prefix }}'; }
	protected function getPackageName()  { return '{{ $lowercase_package_name }}'; }
	protected function getListTitle()    { return [ 
		trans($this->getPackageName() . '::admin.title.main'), 
		trans($this->getPackageName() . '::admin.title.list')
	]; }
	protected function getAddTitle()     { return [ 
		trans($this->getPackageName() . '::admin.title.main'), 
		trans($this->getPackageName() . '::admin.title.add')
	]; }
	protected function getEditTitle()    { return [ 
		trans($this->getPackageName() . '::admin.title.main'), 
		trans($this->getPackageName() . '::admin.title.edit')
	]; }
	
}
