<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHallsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('halls', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('accounting_code')->nullable();
			$table->unsignedInteger('buildings_id')->index('fk_halls_buildings1_idx');
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
		Schema::drop('halls');
	}

}
