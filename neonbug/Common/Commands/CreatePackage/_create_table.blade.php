

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create{{ $model_name }}Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('{{ $table_name }}', function(Blueprint $table)
		{
			$table->increments('{{ 'id_' . str_replace('\\', '', snake_case($model_name)) }}');
			//TODO add columns
			$table->timestamps();
		});
		
		DB::table('role')->insert(
			['id_role' => '{{ $table_name }}', 'name' => '{{ $package_name }} editor', 
				'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')]
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('{{ $table_name }}');
	}

}
