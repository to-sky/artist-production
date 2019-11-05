<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlueprintTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hall_blueprints', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('kartina_id');
            $table->unsignedInteger('building_id');
            $table->integer('revision');
            $table->timestamps();

            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onDelete('cascade');
        });

        Schema::create('zone_blueprints', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('kartina_id');
            $table->unsignedInteger('hall_blueprint_id');
            $table->timestamps();

            $table->foreign('hall_blueprint_id')
                ->references('id')
                ->on('hall_blueprints')
                ->onDelete('cascade');
        });

        Schema::create('place_blueprints', function (Blueprint $table) {
            $table->increments('id');
            $table->string('row')->nullable();
            $table->string('num')->nullable();
            $table->string('text')->nullable();
            $table->string('kartina_id');
            $table->string('template');
            $table->double('x');
            $table->double('y');
            $table->double('width');
            $table->double('height');
            $table->text('path')->nullable();
            $table->double('rotate');
            $table->unsignedInteger('zone_blueprint_id')->nullable();
            $table->unsignedInteger('hall_blueprint_id');
            $table->timestamps();

            $table->foreign('zone_blueprint_id')->references('id')->on('zone_blueprints')->onDelete('cascade');
            $table->foreign('hall_blueprint_id')->references('id')->on('hall_blueprints')->onDelete('cascade');
        });

        Schema::create('label_blueprints', function (Blueprint $table) {
            $table->increments('id');
            $table->double('x');
            $table->double('y');
            $table->unsignedInteger('hall_blueprint_id');
            $table->boolean('is_bold');
            $table->boolean('is_italic');
            $table->integer('layer');
            $table->double('rotation');
            $table->string('text');
            $table->timestamps();

            $table->foreign('hall_blueprint_id')->references('id')->on('hall_blueprints')->onDelete('cascade');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->unsignedInteger('kartina_id')->nullable();
            $table->unsignedInteger('hall_blueprint_id')->nullable();

            $table->foreign('hall_blueprint_id')->references('id')->on('hall_blueprints')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('events_hall_blueprint_id_foreign');

            $table->dropColumn('kartina_id');
            $table->dropColumn('hall_blueprint_id');
        });

        Schema::dropIfExists('label_blueprints');
        Schema::dropIfExists('place_blueprints');
        Schema::dropIfExists('zone_blueprints');
        Schema::dropIfExists('hall_blueprints');
    }
}
