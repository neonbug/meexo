<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function(Blueprint $table)
		{
			$table->increments('id_news');
			$table->boolean('published');
			$table->timestamp('published_from_date');
			$table->integer('id_user');
			$table->string('main_image', 255);
			$table->timestamps();
			
			$table->foreign('id_user')->references('id_user')->on('user');
		});
		
		DB::table('role')->insert(
			['id_role' => 'news', 'name' => 'News editor', 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')]
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('news');
	}

}
