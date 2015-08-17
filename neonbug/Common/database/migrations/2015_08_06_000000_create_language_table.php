<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('language', function(Blueprint $table)
		{
			$table->increments('id_language');
			$table->string('name', 100);
			$table->string('locale', 2);
			$table->timestamps();
		});
		
		DB::table('language')->insert(
			['name' => 'English', 'locale' => 'en', 
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
		Schema::drop('language');
	}

}
