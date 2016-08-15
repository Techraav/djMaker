<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventPlaylistTable extends Migration {

	public function up()
	{
		Schema::create('event_playlist', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->integer('playlist_id')->unsigned();
			$table->integer('event_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('event_playlist');
	}
}