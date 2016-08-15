<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->rememberToken();
			$table->string('first_name', 255);
			$table->string('last_name', 255);
			$table->string('email', 255);
			$table->string('google_id')->index();
			$table->string('google_email')->index();
			$table->string('google_token');
			$table->string('facebook_id')->index();

			$table->string('avatar', 255);
			$table->smallInteger('level')->index()->default('1');
			$table->string('password', 60);
			$table->boolean('bannned')->default(0);
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}