<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttachesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attaches', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('files_id')->index('fk_attach_files1_idx');
			$table->integer('entity_id')->nullable()->index('idx_entity_id');
			$table->string('entity')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('attaches');
	}

}
