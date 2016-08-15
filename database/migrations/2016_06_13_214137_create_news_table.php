<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsTable extends Migration {

	public function up()
	{
		Schema::create('news', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->string('title', 255);
			$table->text('content');
			$table->integer('user_id')->unsigned()->index();
			$table->date('published_at')->index();
			$table->tinyInteger('active')->index()->default('1');
			$table->string('slug', 255)->unique();
		});
	}

	public function down()
	{
		Schema::drop('news');
	}
}