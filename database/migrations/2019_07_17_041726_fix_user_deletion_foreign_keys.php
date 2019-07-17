<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixUserDeletionForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('fk_orders_users1');

            $table->foreign('user_id', 'fk_orders_user_id_users_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('set null')
            ;
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign('tickets_user_id_foreign');

            $table->foreign('user_id', 'tickets_user_id_foreign')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('set null')
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
            $table->dropForeign('fk_orders_user_id_users_id');

            $table->foreign('user_id', 'fk_orders_users1')
                ->references('id')
                ->on('users')
            ;
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign('tickets_user_id_foreign');

            $table->foreign('user_id', 'tickets_user_id_foreign')
                ->references('id')
                ->on('users')
            ;
        });
    }
}
