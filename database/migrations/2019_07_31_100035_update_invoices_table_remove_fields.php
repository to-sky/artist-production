<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInvoicesTableRemoveFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'client_id')) {
                $table->dropForeign('invoices_client_id_foreign');
                $table->dropColumn('client_id');
            }

            $table->dropColumn('type');
            $table->dropColumn('comment');

            $table->string('title');
            $table->integer('file_id')->nullable();

            $table->foreign('file_id')
                ->references('id')
                ->on('files')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('comment');
            $table->string('type');
            $table->dropColumn('title');

            $table->dropForeign(['file_id']);
            $table->dropColumn('file_id');
        });
    }
}
