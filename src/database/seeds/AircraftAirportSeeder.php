<?php

class AircraftAirportSeeder extends BaseSeeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		for ($i = 0; $i < 42; $i++) {
			$aircraftAirport = new \App\AircraftAirport();
			$aircraftAirport->aircraft_id = random_int(1, 15);
			$aircraftAirport->airport_id  = random_int(1, 15);
			$aircraftAirport->save();
		}
	}
}
