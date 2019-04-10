<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePlacesTableAddPositionColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->string('template');
            $table->integer('status');
            $table->float('x');
            $table->float('y');
            $table->float('width');
            $table->float('height');
            $table->text('path')->nullable();
            $table->float('rotate');
            $table->string('row')->nullable()->change();
            $table->string('num')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn(['template', 'status', 'x', 'y', 'width', 'height', 'path', 'rotate']);
            $table->string('row')->nullable(false)->change();
            $table->string('num')->nullable(false)->change();
        });
    }
}
