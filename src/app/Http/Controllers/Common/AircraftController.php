<?php

namespace App\Http\Controllers\Common;

use App\Aircraft;
use App\Http\Requests\Ajax\AircraftCanFlyRequest;

class AircraftController extends CommonController
{
	/**
	 * @param   AircraftCanFlyRequest $request
	 * @return  \Illuminate\Http\JsonResponse
	 */
	public function canFly(AircraftCanFlyRequest $request) {
		$aircraft = Aircraft::find($request->aircraft_id);
		$result = $aircraft->range >= $request->distance;
		return response()->json($result);
	}
}
