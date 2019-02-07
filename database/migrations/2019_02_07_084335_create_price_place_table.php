<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricePlaceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('price_place', function(Blueprint $table)
		{
			$table->integer('place_id')->index('fk_places_has_prices_places1_idx');
			$table->integer('price_id')->index('fk_places_has_prices_prices1_idx');
			$table->primary(['place_id','price_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('price_place');
	}

}
