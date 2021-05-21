<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixBillingAddressAndShippingAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_addresses', function (Blueprint $table) {
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->string('street')->nullable()->change();
            $table->string('house')->nullable()->change();
            $table->string('apartment')->nullable()->change();
            $table->string('post_code')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('country')->nullable()->change();
        });

        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->string('street')->nullable()->change();
            $table->string('house')->nullable()->change();
            $table->string('apartment')->nullable()->change();
            $table->string('post_code')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('country')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_addresses', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
            $table->string('street')->nullable(false)->change();
            $table->string('house')->nullable(false)->change();
            $table->string('apartment')->nullable(false)->change();
            $table->string('post_code')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('country')->nullable(false)->change();
        });

        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
            $table->string('street')->nullable(false)->change();
            $table->string('house')->nullable(false)->change();
            $table->string('apartment')->nullable(false)->change();
            $table->string('post_code')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('country')->nullable(false)->change();
        });
    }
}
