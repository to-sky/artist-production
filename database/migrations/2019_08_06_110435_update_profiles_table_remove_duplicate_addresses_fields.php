<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProfilesTableRemoveDuplicateAddressesFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'email',
                'street',
                'house',
                'apartment',
                'post_code',
                'city'
            ]);

            $table->dropForeign('fk_clients_countries1');
            $table->dropIndex('fk_clients_countries1_idx');
            $table->dropColumn('country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable()->unique('email_UNIQUE');
            $table->string('street')->nullable();
            $table->string('house')->nullable();
            $table->string('apartment')->nullable();
            $table->string('post_code')->nullable();
            $table->string('city')->nullable();

            $table->integer('country_id')
                ->unsigned()
                ->nullable()
                ->index('fk_clients_countries1_idx');

            $table->foreign('country_id', 'fk_clients_countries1')
                  ->references('id')
                  ->on('countries')
                  ->onUpdate('CASCADE')
                  ->onDelete('SET NULL');
        });
    }
}
