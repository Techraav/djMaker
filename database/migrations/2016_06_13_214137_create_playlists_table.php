<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
class CreatePlaylistsTable extends Migration {

	public function up()
	{
		Schema::create('playlists', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->text('description');
			$table->tinyInteger('active')->index()->default('1');
			$table->string('name');
		});
	}

	public function down()
	{
		Schema::drop('playlists');
	}
}