<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('translation_source', function(Blueprint $table)
		{
			$table->string('id_translation_source', 255);
			$table->timestamps();
			
			$table->primary('id_translation_source');
		});
		
		Schema::create('translation', function(Blueprint $table)
		{
			$table->increments('id_translation');
			$table->string('id_translation_source', 255);
			$table->integer('id_language');
			$table->text('value');
			$table->timestamps();
			
			$table->foreign('id_translation_source')->references('id_translation_source')->on('translation_source');
			$table->foreign('id_language')->references('id_language')->on('language');
			
			$table->index('id_translation_source');
			$table->index('id_language');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('role');
	}

}
