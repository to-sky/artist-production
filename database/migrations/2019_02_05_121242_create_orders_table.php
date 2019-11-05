<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('status', 45)->nullable();
			$table->dateTime('expired_at')->nullable();
			$table->float('tax', 10, 0)->nullable();
			$table->float('discount', 10, 0)->nullable();
			$table->float('final_price', 10, 0)->nullable();
			$table->integer('paid_bonuses')->nullable();
			$table->string('paid_cash', 45)->nullable();
			$table->string('payment_type', 45)->nullable();
			$table->string('delivery_type', 45)->nullable();
			$table->string('delivery_status', 45)->nullable();
			$table->text('comment', 65535)->nullable();
			$table->dateTime('paid_at')->nullable();
			$table->integer('user_id')->unsigned()->index('fk_orders_users1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders');
	}

}
