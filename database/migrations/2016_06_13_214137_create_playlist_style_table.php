<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlaylistStyleTable extends Migration {

	public function up()
	{
		Schema::create('playlist_style', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('playlist_id')->unsigned()->index();
			$table->integer('style_id')->unsigned()->index();
		});
	}

	public function down()
	{
		Schema::drop('playlist_style');
	}
}