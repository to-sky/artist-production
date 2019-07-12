<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrdersTableDeleteFinalPriceField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('final_price');
            $table->unsignedInteger('payer_id')->nullable();
            $table->unsignedInteger('manager_id')->nullable();

            $table->foreign('payer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
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
            $table->dropForeign('payer_id');
            $table->dropForeign('manager_id');

            $table->dropColumn('payer_id');
            $table->dropColumn('manager_id');

            $table->decimal('final_price')->nullable();
        });
    }
}
