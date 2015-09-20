<?php use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryImageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return  void
	 */
	public function up()
	{
		Schema::create('gallery_image', function(Blueprint $table)
		{
			$table->increments('id_gallery_image');
			$table->integer('id_language')->nullable();
			$table->string('table_name', 60);
			$table->string('column_name', 60);
			$table->string('image', 255);
			$table->integer('id_row');
			$table->integer('ord');
			$table->timestamps();
			
			$table->foreign('id_language')->references('id_language')->on('language');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return  void
	 */
	public function down()
	{
		Schema::drop('gallery_image');
	}

}
