<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration {

	public function up()
	{
		Schema::create('events', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->string('name', 255);
			$table->integer('user_id')->unsigned()->index();
			$table->string('slug', 255)->unique();
			$table->text('description');
			$table->boolean('private')->default(0);
			$table->date('date')->index();
			$table->time('start');
			$table->time('end');
			$table->tinyInteger('active')->index()->default('1');
			$table->string('city', 255);
			$table->string('adress');
		});
	}

	public function down()
	{
		Schema::drop('events');
	}
}