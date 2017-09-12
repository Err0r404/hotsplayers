<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('games', function(Blueprint $table) {
			$table->foreign('map_id')->references('id')->on('maps')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->foreign('player_id')->references('id')->on('players')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->foreign('hero_id')->references('id')->on('heroes')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->foreign('game_id')->references('id')->on('games')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('games', function(Blueprint $table) {
			$table->dropForeign('games_map_id_foreign');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->dropForeign('participations_player_id_foreign');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->dropForeign('participations_hero_id_foreign');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->dropForeign('participations_game_id_foreign');
		});
	}
}