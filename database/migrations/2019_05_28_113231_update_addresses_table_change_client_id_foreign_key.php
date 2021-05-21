<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAddressesTableChangeClientIdForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign('fk_addreses_clients1');
            $table->dropIndex('fk_addreses_clients1_idx');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->unsignedInteger('client_id')->change();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->increments('id')->change();
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['client_id']);

            $table->index('client_id', 'fk_addreses_clients1_idx');
            $table->foreign('client_id', 'fk_addreses_clients1')->references('id')->on('clients')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }
}
