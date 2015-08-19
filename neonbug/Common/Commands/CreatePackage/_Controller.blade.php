namespace Neonbug\{{ $package_name }}\Controllers;

use App;
use \Neonbug\{{ $package_name }}\Models\{{ $model_name }} as Model;

class Controller extends \App\Http\Controllers\Controller {
	
	const PACKAGE_NAME = '{{ $lowercase_package_name }}';
	
	public function __construct()
	{
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
			$item = Model::find($id);
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
	
}
