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
		
		/* insert default languages */
		$languages = Config::get('neonbug.common.languages', [
			'en' => 'English'
		]);
		
		$insert_arr = [];
		foreach ($languages as $locale=>$name)
		{
			$insert_arr[] = [ 'name' => $name, 'locale' => $locale, 
				'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d') ];
		}
		
		DB::table('language')->insert($insert_arr);
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
