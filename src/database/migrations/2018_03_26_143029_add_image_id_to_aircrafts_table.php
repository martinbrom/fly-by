<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageIdToAircraftsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('aircrafts', function (Blueprint $table) {
			$table->integer('image_id')->unsigned()->nullable()->after('id');
		});

		Schema::table('aircrafts', function (Blueprint $table) {
			$table->foreign('image_id')->references('id')->on('aircraft_images');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('aircrafts', function (Blueprint $table) {
        	$table->dropForeign(['image_id']);
			$table->dropColumn('image_id');
		});
	}
}
