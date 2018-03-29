<?php

namespace App\Http\Controllers;

use App\Aircraft;
use App\Airport;
use App\Http\Requests\AircraftAirportAddRequest;
use App\Http\Requests\AircraftAirportRemoveRequest;

class AircraftAirportController extends LoggedOnlyController
{
	/**
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		// TODO: Pagination
		$aircrafts = Aircraft::all();
		$airports  = Airport::all();
		return view('aircraft_airports.index', compact('aircrafts', 'airports'));
	}

	public function add(AircraftAirportAddRequest $request) {

	}

	public function remove(AircraftAirportRemoveRequest $request) {

	}
}
