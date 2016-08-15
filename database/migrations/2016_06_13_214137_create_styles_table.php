<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStylesTable extends Migration {

	public function up()
	{
		Schema::create('styles', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
		});
	}

	public function down()
	{
		Schema::drop('styles');
	}
}