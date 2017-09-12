<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHeroesTable extends Migration {

	public function up()
	{
		Schema::create('heroes', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('heroes');
	}
}