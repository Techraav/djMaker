<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVideosTable extends Migration {

	public function up()
	{
		Schema::create('videos', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->string('url', 255)->unique();
			$table->string('artist', 255);
			$table->string('name')->index();
			$table->string('tags')->index();
		});
	}

	public function down()
	{
		Schema::drop('videos');
	}
}