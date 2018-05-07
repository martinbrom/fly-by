<?php

namespace App\Http\Controllers\Common;

use App\Airport;
use App\Http\Controllers\CommonController;
use App\Http\Requests\Ajax\AircraftsAtAirportRequest;

class AirportController extends CommonController
{
	/**
	 * @return  \Illuminate\Http\JsonResponse
	 */
	public function index() {
		$airports = Airport::getAllWithAircrafts();
		return response()->json($airports);
	}

	/**
	 * @param   AircraftsAtAirportRequest $request
	 * @return  \Illuminate\Http\JsonResponse
	 */
	public function aircrafts(AircraftsAtAirportRequest $request) {
		$airport = Airport::find($request->airport_id);
		$aircrafts = $airport->aircrafts()->get();
		return response()->json($aircrafts);
	}
}
