<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAttachesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('attaches', function(Blueprint $table)
		{
			$table->foreign('files_id', 'fk_attach_files1')->references('id')->on('files')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('attaches', function(Blueprint $table)
		{
			$table->dropForeign('fk_attach_files1');
		});
	}

}
