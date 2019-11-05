<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrdersAddServicePrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->double('service_price')->default(0);
        });

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->unsignedInteger('price_type')->default(0);
            $table->double('price_amount')->default(0);
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
            $table->dropColumn('service_price');
        });

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('price_type');
            $table->dropColumn('price_amount');
        });
    }
}
