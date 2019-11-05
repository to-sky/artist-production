<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->double('subtotal')->default(0);
            $table->unsignedInteger('payment_method_id')->nullable();

            $table->foreign('payment_method_id', 'fk_orders_payment_method_id_payment_methods_id')
                ->references('id')
                ->on('payment_methods')
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('fk_orders_payment_method_id_payment_methods_id');

            $table->dropColumn('payment_method_id');
            $table->dropColumn('subtotal');
        });
    }
}
