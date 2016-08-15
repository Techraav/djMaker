<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesTable extends Migration {

	public function up()
	{
		Schema::create('articles', function(Blueprint $table) {
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('event_id')->unsigned()->index()->default('1');
			$table->string('title', 255);
			$table->text('content');
			$table->string('slug', 255)->unique();
			$table->tinyInteger('active')->index()->default('1');
		});
	}

	public function down()
	{
		Schema::drop('articles');
	}
}