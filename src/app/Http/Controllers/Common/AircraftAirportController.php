<?php

namespace App\Http\Controllers\Common;

use App\AircraftAirport;
use App\Http\Controllers\CommonController;

/**
 * Class AircraftAirportController
 *
 * @package App\Http\Controllers\Common
 * @author  Martin Brom
 */
class AircraftAirportController extends CommonController
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $aircraftAirport = AircraftAirport::with('aircraft', 'airport')->findOrFail($id);

        return response()->json($aircraftAirport);
    }
}
