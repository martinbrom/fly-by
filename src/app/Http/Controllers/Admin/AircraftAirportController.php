<?php

namespace App\Http\Controllers\Admin;

use App\AircraftAirport;
use App\Http\Controllers\AdminController;
use App\Http\Requests\AircraftAirportStoreRequest;
use App\Http\Requests\AircraftAirportUpdateRequest;

class AircraftAirportController extends AdminController
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
		return redirect()->route('admin.airports.show', $request->airport_id);
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
		$airport_id = $aircraftAirport->airport_id;
		$aircraftAirport->fill($request->all());
		$aircraftAirport->save();
		return redirect()->route('admin.airports.show', $airport_id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$aircraftAirport = AircraftAirport::findOrFail($id);
		$airport_id = $aircraftAirport->airport_id;
		$aircraftAirport->delete();
		return redirect()->route('admin.airports.show', $airport_id);
	}
}
