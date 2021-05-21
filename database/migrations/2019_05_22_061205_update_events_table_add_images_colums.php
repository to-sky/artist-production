<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventsTableAddImagesColums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('event_image_id')->nullable();
            $table->integer('free_pass_logo_id')->nullable();

            $table->foreign('event_image_id')->references('id')->on('files')->onDelete('set null');
            $table->foreign('free_pass_logo_id')->references('id')->on('files')->onDelete('set null');
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
            $table->dropForeign(['event_image_id']);
            $table->dropColumn('event_image_id');

            $table->dropForeign(['free_pass_logo_id']);
            $table->dropColumn('free_pass_logo_id');
        });
    }
}
