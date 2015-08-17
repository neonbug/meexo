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
		$repo = App::make('\Neonbug\{{ $package_name }}\Repositories\{{ $model_name }}Repository');
		$items = $repo->getLatest();
		
		$language = App::make('Language');
		App::make('ResourceRepository')->inflateObjectsWithValues($items, $language->id_language);
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(static::PACKAGE_NAME, 'index', [ 'items' => $items ]);
	}
	
	public function item($id)
	{
		$item = Model::findOrFail($id);
		
		$language = App::make('Language');
		App::make('ResourceRepository')->inflateObjectWithValues($item, $language->id_language);
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(static::PACKAGE_NAME, 'item', [ 'item' => $item ]);
	}
	
}
