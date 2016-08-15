<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLikesTable extends Migration {

	public function up()
	{
		Schema::create('likes', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->integer('user_id')->unsigned()->index();
			$table->integer('video_id')->unsigned()->index();
			$table->tinyInteger('value')->default('1');
		});
	}

	public function down()
	{
		Schema::drop('likes');
	}
}