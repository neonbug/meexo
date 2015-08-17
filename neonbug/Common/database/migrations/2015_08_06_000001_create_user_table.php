<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user', function(Blueprint $table)
		{
			$table->increments('id_user');
			$table->string('name');
			$table->string('username')->unique();
			$table->string('password', 60);
			$table->rememberToken();
			$table->timestamps();
		});
		
		DB::table('user')->insert(
			['name' => 'Administrator', 'username' => 'admin', 'password' => bcrypt('admin'), 
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
		Schema::drop('user');
	}

}
