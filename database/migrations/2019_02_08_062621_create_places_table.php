<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlacesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('places', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('row_num');
			$table->string('place_num');
			$table->string('place_text')->nullable();
			$table->string('help_text')->nullable();
            $table->unsignedInteger('halls_id')->index('fk_places_halls1_idx');
            $table->unsignedInteger('zones_id')->index('fk_places_zones1_idx');
            $table->timestamps();
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('places');
	}

}
