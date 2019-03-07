<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('addresses', function(Blueprint $table)
		{
			$table->foreign('client_id', 'fk_addreses_clients1')->references('id')->on('clients')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('country_id', 'fk_addreses_countries1')->references('id')->on('countries')->onUpdate('CASCADE')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('addresses', function(Blueprint $table)
		{
			$table->dropForeign('fk_addreses_clients1');
			$table->dropForeign('fk_addreses_countries1');
		});
	}

}
