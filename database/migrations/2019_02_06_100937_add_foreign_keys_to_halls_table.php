<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHallsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('halls', function(Blueprint $table)
		{
			$table->foreign('buildings_id', 'fk_halls_buildings1')
                ->references('id')
                ->on('buildings')
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
		Schema::table('halls', function(Blueprint $table)
		{
			$table->dropForeign('fk_halls_buildings1');
		});
	}

}
