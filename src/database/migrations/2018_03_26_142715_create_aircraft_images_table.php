<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAircraftImagesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('aircraft_images', function (Blueprint $table) {
			$table->increments('id');
			$table->string('path', 50);
			$table->string('description', 50)->nullable();
			$table->timestamps();

			$table->unique('id');
			$table->unique('path');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('aircraft_images');
	}
}
