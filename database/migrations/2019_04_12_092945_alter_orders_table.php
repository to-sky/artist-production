<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Shipping;

class AlterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function(Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->dropColumn('delivery_type');
            $table->dropColumn('delivery_status');
            $table->unsignedInteger('shipping_zone_id')->nullable();
            $table->float('courier_price')->default(0);
            $table->float('shipping_price')->default(0);
            $table->tinyInteger('shipping_status')->default(Shipping::STATUS_IN_PROCESSING);
            $table->tinyInteger('shipping_type')->default(Shipping::TYPE_DELIVERY);
            $table->string('shipping_comment')->nullable();
            $table->string('status_comment')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('shipping_zone_id')
                ->references('id')
                ->on('shipping_zones')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function(Blueprint $table) {
            $table->string('payment_type');
            $table->string('delivery_type');
            $table->string('delivery_status');
            $table->dropForeign('orders_shipping_zone_id_foreign');
            $table->dropColumn('shipping_zone_id');
            $table->dropColumn('courier_price');
            $table->dropColumn('shipping_price');
            $table->dropColumn('shipping_status');
            $table->dropColumn('shipping_type');
            $table->dropColumn('shipping_comment');
            $table->dropColumn('status_comment');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
