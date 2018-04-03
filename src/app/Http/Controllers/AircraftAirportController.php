<?php

namespace App\Http\Controllers;

use App\AircraftAirport;
use App\Http\Requests\AircraftAirportStoreRequest;
use App\Http\Requests\AircraftAirportUpdateRequest;

class AircraftAirportController extends LoggedOnlyController
{
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param AircraftAirportStoreRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(AircraftAirportStoreRequest $request) {
		$aircraftAirport = new AircraftAirport($request->all());
		$aircraftAirport->save();
		return response()->json(true);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  AircraftAirportUpdateRequest $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(AircraftAirportUpdateRequest $request, $id) {
		$aircraftAirport = AircraftAirport::findOrFail($id);
		$aircraftAirport->fill($request->all());
		$aircraftAirport->save();
		return response()->json(true);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$aircraftAirport = AircraftAirport::findOrFail($id);
		$aircraftAirport->delete();
		return response()->json(true);
	}
}
