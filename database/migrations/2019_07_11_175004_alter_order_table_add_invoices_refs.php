<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderTableAddInvoicesRefs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('provisional_invoice_id')->nullable();
            $table->integer('final_invoice_id')->nullable();

            $table->foreign('provisional_invoice_id', 'fk_provisional_invoice_file')
                ->references('id')
                ->on('files')
                ->onDelete('SET NULL')
                ->onUpdate('SET NULL')
            ;

            $table->foreign('final_invoice_id', 'fk_final_invoice_file')
                ->references('id')
                ->on('files')
                ->onDelete('SET NULL')
                ->onUpdate('SET NULL')
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
            $table->dropForeign('fk_final_invoice_file');
            $table->dropForeign('fk_provisional_invoice_file');

            $table->dropColumn('final_invoice_id');
            $table->dropColumn('provisional_invoice_id');
        });
    }
}
