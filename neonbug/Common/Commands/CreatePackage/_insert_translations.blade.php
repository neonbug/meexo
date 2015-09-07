

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Insert{{ $model_name }}Translations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$migration_helper = App::make('\Neonbug\Common\Helpers\MigrationHelper');
		$migration_helper->insertTranslations(__DIR__ . '/translations.{{ $lowercase_package_name }}/');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	}

}
