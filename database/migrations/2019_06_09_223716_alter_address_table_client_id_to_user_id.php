<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddressTableClientIdToUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['client_id']);

            $table->dropColumn('client_id');

            $table->unsignedInteger('user_id');

            $table->foreign('user_id', 'fk_addresses_user_id_users_id')
                ->references('id')
                ->on('users')
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
            $table->dropForeign('fk_addresses_user_id_users_id');

            $table->dropColumn('user_id');

            $table->unsignedInteger('client_id');

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('CASCADE');
        });
    }
}
