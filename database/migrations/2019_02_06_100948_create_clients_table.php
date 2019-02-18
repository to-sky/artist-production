<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clients', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('email')->nullable()->unique('email_UNIQUE');
			$table->string('phone', 45)->nullable();
			$table->string('street')->nullable();
			$table->string('house')->nullable();
			$table->string('apartment')->nullable();
			$table->string('post_code')->nullable();
			$table->string('city')->nullable();
			$table->string('code')->nullable();
			$table->float('comission')->nullable();
			$table->text('comment', 65535)->nullable();
			$table->string('type', 45)->nullable();
            $table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('clients');
	}

}
