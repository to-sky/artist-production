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
            $table->unsignedInteger('shipping_id')->nullable();
            $table->tinyInteger('shipping_status')->default(Shipping::STATUS_IN_PROCESSING);
            $table->tinyInteger('shipping_type')->default(Shipping::TYPE_DELIVERY);
            $table->string('shipping_comment')->nullable();
            $table->string('status_comment')->nullable();
            $table->timestamps();

            $table->foreign('shipping_id')
                ->references('id')
                ->on('shippings')
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
            $table->dropForeign('orders_shipping_id_foreign');
            $table->dropColumn('shipping_id');
            $table->dropColumn('shipping_status');
            $table->dropColumn('shipping_type');
            $table->dropColumn('shipping_comment');
            $table->dropColumn('status_comment');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
