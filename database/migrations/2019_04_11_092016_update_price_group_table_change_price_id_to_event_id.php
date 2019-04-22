<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePriceGroupTableChangePriceIdToEventId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_groups', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign(['price_id']);
            $table->dropColumn(['price_id']);
            Schema::enableForeignKeyConstraints();

            $table->unsignedInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_groups', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign(['event_id']);
            $table->dropColumn(['event_id']);
            Schema::enableForeignKeyConstraints();

            if (! Schema::hasColumn('price_groups', 'price_id')) {
                $table->unsignedInteger('price_id');
            }

            $table->foreign('price_id')->references('id')->on('prices')->onDelete('cascade');
        });
    }
}
