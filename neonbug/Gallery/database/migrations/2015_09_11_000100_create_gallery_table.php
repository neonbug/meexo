<?php use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return  void
	 */
	public function up()
	{
		Schema::create('gallery', function(Blueprint $table)
		{
			$table->increments('id_gallery');
			$table->boolean('published');
			$table->string('main_image', 255);
			$table->integer('ord');
			$table->timestamps();
		});
		
		DB::table('role')->insert(
			['id_role' => 'gallery', 'name' => 'Gallery editor', 
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
		Schema::drop('gallery');
	}

}
