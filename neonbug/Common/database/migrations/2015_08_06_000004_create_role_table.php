<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('role', function(Blueprint $table)
		{
			$table->string('id_role', 255);
			$table->string('name', 255);
			$table->timestamps();
			
			$table->primary('id_role');
		});
		
		Schema::create('user_role', function(Blueprint $table)
		{
			$table->increments('id_user_role');
			$table->integer('id_user');
			$table->string('id_role', 255);
			$table->timestamps();
		});
		
		DB::table('role')->insert(
			['id_role' => 'admin', 'name' => 'Administrator', 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')]
		);
		
		DB::table('user_role')->insert(
			['id_role' => 'admin', 'id_user' => 1 /* admin user */, 'created_at' => date('Y-m-d'), 
				'updated_at' => date('Y-m-d')]
		);
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
