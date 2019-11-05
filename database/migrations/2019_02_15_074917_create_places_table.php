<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('row');
            $table->string('num');
            $table->string('text')->nullable();
            $table->string('kartina_id');
            $table->unsignedInteger('zone_id')->nullable();
            $table->unsignedInteger('hall_id');
            $table->timestamps();

            $table->foreign('zone_id')->references('id')->on('zones');
            $table->foreign('hall_id')->references('id')->on('halls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
