<?php

class AirportSeeder extends BaseSeeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		factory(\App\Airport::class, 15)->create();
	}
}
