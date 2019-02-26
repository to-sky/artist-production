<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price');
            $table->unsignedInteger('events_id');
            $table->unsignedInteger('places_id');
            $table->timestamps();

            $table->unique('events_id', 'places_id');

            $table->foreign('events_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('places_id')->references('id')->on('places')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
}
