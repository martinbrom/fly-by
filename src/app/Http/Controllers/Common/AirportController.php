<?php

namespace App\Http\Controllers\Common;

use App\AircraftAirport;
use App\Airport;
use App\Http\Controllers\CommonController;
use App\Http\Requests\Ajax\AircraftsAtAirportRequest;

/**
 * Class AirportController
 *
 * @package App\Http\Controllers\Common
 * @author  Martin Brom
 */
class AirportController extends CommonController
{
    /**
     * @return  \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $airports = Airport::getAllWithAircrafts();

        return response()->json($airports);
    }

    /**
     * @param   AircraftsAtAirportRequest $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function aircrafts(AircraftsAtAirportRequest $request)
    {
        $aircraftAirports = AircraftAirport::where('airport_id', '=', $request->airport_id)
            ->with('aircraft')
            ->get();

        return response()->json($aircraftAirports);
    }

    /**
     * @param AircraftsAtAirportRequest $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function otherAircrafts(AircraftsAtAirportRequest $request)
    {
        $aircraftAirports = AircraftAirport::where('airport_id', '!=', $request->airport_id)
            ->with('aircraft', 'airport')
            ->get();

        return response()->json($aircraftAirports);
    }
}
