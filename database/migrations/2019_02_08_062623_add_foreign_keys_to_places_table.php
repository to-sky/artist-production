<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPlacesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('places', function(Blueprint $table)
		{
			$table->foreign('halls_id', 'fk_places_halls1')
                ->references('id')
                ->on('halls')
                ->onUpdate('cascade')
                ->onDelete('cascade');

			$table->foreign('zones_id', 'fk_places_zones1')
                ->references('id')
                ->on('zones')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('places', function(Blueprint $table)
		{
			$table->dropForeign('fk_places_halls1');
			$table->dropForeign('fk_places_zones1');
		});
	}

}
