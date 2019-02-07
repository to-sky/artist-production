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
			$table->integer('id')->primary();
			$table->string('row_num', 191);
			$table->string('place_num', 191);
			$table->string('place_text', 191)->nullable();
			$table->string('help_text', 191)->nullable();
			$table->integer('zones_id')->index('fk_places_zones1_idx');
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
