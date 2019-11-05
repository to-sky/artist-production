<?php

use App\Models\Shipping;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrdersTableChangeShippingDefaultStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->smallInteger('shipping_status')->default(Shipping::STATUS_NOT_SET)->nullable()->change();
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->smallInteger('shipping_type')->default(null)->nullable()->change();
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
            $table->smallInteger('shipping_status')->default(Shipping::STATUS_IN_PROCESSING)->change();
            $table->unsignedInteger('user_id')->nullable(false)->change();
            $table->smallInteger('shipping_type')->nullable(false)->default(0)->change();
        });
    }
}
