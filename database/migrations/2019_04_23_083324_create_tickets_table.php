<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount_printed')->default(0);
            $table->decimal('price')->nullable();
            $table->integer('status')->default(0);
            $table->boolean('is_checked')->default(0);
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('place_id');
            $table->unsignedInteger('price_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('place_id')->references('id')->on('places');
            $table->foreign('price_id')->references('id')->on('prices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
