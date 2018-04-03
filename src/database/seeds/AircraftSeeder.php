<?php

class AircraftSeeder extends BaseSeeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		factory(\App\Aircraft::class, 15)->create();
	}
}
