<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGamesTable extends Migration {

	public function up()
	{
		Schema::create('games', function(Blueprint $table) {
			$table->increments('id');
			$table->string('type');
			$table->datetime('date');
			$table->integer('length');
			$table->integer('api_id')->unique();
			$table->timestamps();
			$table->integer('map_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('games');
	}
}