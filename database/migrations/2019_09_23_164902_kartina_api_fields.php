<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KartinaApiFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('kartina_id')->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('kartina_id')->nullable();
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
            $table->dropColumn('kartina_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kartina_id');
        });
    }
}
