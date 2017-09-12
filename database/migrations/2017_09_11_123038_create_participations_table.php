<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParticipationsTable extends Migration {

	public function up()
	{
		Schema::create('participations', function(Blueprint $table) {
			$table->increments('id');
			$table->boolean('win');
			$table->timestamps();
			$table->integer('player_id')->unsigned();
			$table->integer('hero_id')->unsigned();
			$table->integer('game_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('participations');
	}
}