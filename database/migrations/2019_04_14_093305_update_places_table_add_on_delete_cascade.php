<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePlacesTableAddOnDeleteCascade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table)
        {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign(['hall_id']);
            $table->dropForeign(['zone_id']);
            Schema::enableForeignKeyConstraints();

            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('set null');
            $table->foreign('hall_id')->references('id')->on('halls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('places', function (Blueprint $table)
        {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign(['hall_id']);
            $table->dropForeign(['zone_id']);
            Schema::enableForeignKeyConstraints();

            $table->foreign('zone_id')->references('id')->on('zones');
            $table->foreign('hall_id')->references('id')->on('halls');
        });
    }
}
