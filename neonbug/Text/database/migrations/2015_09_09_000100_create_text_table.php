<?php use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return  void
	 */
	public function up()
	{
		Schema::create('text', function(Blueprint $table)
		{
			$table->increments('id_text');
			//TODO add columns
			$table->timestamps();
		});
		
		DB::table('role')->insert(
			['id_role' => 'text', 'name' => 'Text editor', 
				'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')]
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return  void
	 */
	public function down()
	{
		Schema::drop('text');
	}

}
