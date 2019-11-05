<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketsTableAddKartinaId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedInteger('kartina_id')->nullable();
        });

        Schema::table('places', function (Blueprint $table) {
            $table->unsignedInteger('price_id')->nullable();

            $table->foreign('price_id')->references('id')->on('prices')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('prices', function (Blueprint $table) {
            $table->unsignedInteger('event_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('kartina_id');
        });

        Schema::table('places', function (Blueprint $table) {
            $table->dropForeign('places_price_id_foreign');

            $table->dropColumn('price_id');
        });

        Schema::table('prices', function (Blueprint $table) {
            $table->unsignedInteger('event_id')->nullable(false)->change();
        });
    }
}
