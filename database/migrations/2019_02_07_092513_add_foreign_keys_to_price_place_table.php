<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPricePlaceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('price_place', function(Blueprint $table)
		{
			$table->foreign('place_id', 'fk_places_has_prices_places1')
                ->references('id')
                ->on('places')
                ->onUpdate('cascade')
                ->onDelete('cascade');

			$table->foreign('price_id', 'fk_places_has_prices_prices1')
                ->references('id')
                ->on('prices')
                ->onUpdate('cascade')
                ->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('price_place', function(Blueprint $table)
		{
			$table->dropForeign('fk_places_has_prices_places1');
			$table->dropForeign('fk_places_has_prices_prices1');
		});
	}

}
