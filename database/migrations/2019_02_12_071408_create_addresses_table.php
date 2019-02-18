<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('addresses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('street')->nullable();
			$table->string('house')->nullable();
			$table->string('apartment')->nullable();
			$table->string('post_code')->nullable();
			$table->string('city')->nullable();
            $table->integer('country_id')->unsigned()->nullable()->index('fk_addreses_countries1_idx');
			$table->integer('client_id')->index('fk_addreses_clients1_idx');
			$table->boolean('active')->default(0);
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
		Schema::drop('addresses');
	}

}
