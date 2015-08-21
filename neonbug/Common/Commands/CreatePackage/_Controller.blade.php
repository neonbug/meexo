namespace Neonbug\{{ $package_name }}\Controllers;

use App;

class Controller extends \App\Http\Controllers\Controller {
	
	const PACKAGE_NAME  = '{{ $lowercase_package_name }}';
	const PREFIX        = '{{ $route_prefix }}';
	const CONFIG_PREFIX = 'neonbug.{{ $config_prefix }}';
	
	private $model;
	
	public function __construct()
	{
		$this->model = config(static::CONFIG_PREFIX . '.model');
	}
	
	public function index()
	{
		$get_items = function() {
			$repo = App::make('\Neonbug\{{ $package_name }}\Repositories\{{ $model_name }}Repository');
			$items = $repo->getLatest();
			
			$language = App::make('Language');
			App::make('ResourceRepository')->inflateObjectsWithValues($items, $language->id_language);
			
			return $items;
		};
		
		$items = (!App::environment('production') ? $get_items() : 
			Cache::rememberForever(static::PACKAGE_NAME . '::items', $get_items));
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(static::PACKAGE_NAME, 'index', [ 'items' => $items ]);
	}
	
	public function item($id)
	{
		$get_item = function() use ($id) {
			$model = $this->model;
			$item = $model::find($id);
			if ($item != null)
			{
				$language = App::make('Language');
				App::make('ResourceRepository')->inflateObjectWithValues($item, $language->id_language);
			}
			
			return $item;
		};
		
		$item = (!App::environment('production') ? $get_item() : 
			Cache::rememberForever(static::PACKAGE_NAME . '::item::' . $id, $get_item));
		if ($item == null)
		{
			abort(404);
		}
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(static::PACKAGE_NAME, 'item', [ 'item' => $item ]);
	}
	
	public function preview($key)
	{
		$item_data = Cache::get(static::PREFIX . '::admin::preview::' . $key);
		if ($item_data == null) abort(404);
		
		$id_item                         = $item_data['id_item'];
		$id_user                         = $item_data['id_user'];
		$fields                          = $item_data['fields'];
		$allowed_lang_independent_fields = $item_data['allowed_lang_independent_fields'];
		$allowed_lang_dependent_fields   = $item_data['allowed_lang_dependent_fields'];
		
		$model = $this->model;
		$item = ($id_item == -1 ? new $model() : $model::findOrFail($id_item));
		
		$admin_helper = App::make('\Neonbug\Common\Helpers\AdminHelper');
		$values = $admin_helper->fillItem($item, $fields, $allowed_lang_independent_fields, 
			$allowed_lang_dependent_fields);
		
		$language = App::make('Language');
		$id_lang = $language->id_language;
		if (array_key_exists($id_lang, $values))
		{
			foreach ($values[$id_lang] as $field_name=>$field_value)
			{
				$item->$field_name = $field_value;
			}
		}
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(static::PACKAGE_NAME, 'item', [ 'item' => $item ]);
	}
	
}
