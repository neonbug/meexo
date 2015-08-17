<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resource', function(Blueprint $table)
		{
			$table->increments('id_resource');
			$table->integer('id_language');
			$table->string('table_name', 60);
			$table->string('column_name', 60);
			$table->string('value');
			$table->integer('id_row');
			$table->timestamps();
			
			$table->foreign('id_language')->references('id_language')->on('language');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resource');
	}

}
