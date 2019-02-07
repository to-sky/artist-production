<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToZonesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('zones', function(Blueprint $table)
		{
			$table->foreign('halls_id', 'fk_zones_halls1')
                ->references('id')
                ->on('halls')
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
		Schema::table('zones', function(Blueprint $table)
		{
			$table->dropForeign('fk_zones_halls1');
		});
	}

}
