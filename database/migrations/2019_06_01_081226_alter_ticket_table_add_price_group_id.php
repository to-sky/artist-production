<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketTableAddPriceGroupId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedInteger('price_group_id')->nullable();

            $table->foreign('price_group_id', 'ticket_price_group_id_foreign')
                ->references('id')
                ->on('price_groups')
                ->onDelete('set null')
                ->onUpdate('cascade')
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
        Schema::table('tickets' , function (Blueprint $table) {
            $table->dropForeign('ticket_price_group_id_foreign');

            $table->dropColumn('price_group_id');
        });
    }
}
