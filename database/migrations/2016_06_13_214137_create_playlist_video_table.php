<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlaylistVideoTable extends Migration {

	public function up()
	{
		Schema::create('playlist_video', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->integer('video_id')->unsigned()->index();
			$table->integer('playlist_id')->unsigned()->index();
			$table->tinyInteger('validation')->index()->default('0');
			$table->integer('user_id')->unsigned()->default('1');
			$table->integer('score')->default('0');
		});
	}

	public function down()
	{
		Schema::drop('playlist_video');
	}
}