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
            $table->integer('shipping_id');
            $table->tinyInteger('shipping_status')->default(Shipping::STATUS_IN_PROCESSING);
            $table->tinyInteger('shipping_type')->default(Shipping::TYPE_DELIVERY);
            $table->string('shipping_comment')->nullable();
            $table->string('status_comment')->nullable();

            $table->foreign('shipping_id')
                ->references('id')
                ->on('shippings')
                ->onDelete('set null');
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
            $table->dropColumn('shipping_id');
        });
    }
}
