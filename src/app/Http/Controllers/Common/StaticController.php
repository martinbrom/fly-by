<?php

namespace App\Http\Controllers\Common;

use App\Aircraft;
use App\AircraftAirport;
use App\Airport;
use App\Http\Controllers\CommonController;

/**
 * Class StaticController
 * Contains functions to display all
 * application static pages such as
 * landing page or map page
 *
 * @package App\Http\Controllers
 */
class StaticController extends CommonController
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $airports = Airport::getAllWithAircrafts();

        return response()->view('index', compact('airports'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function map()
    {
        $airports         = Airport::getAllWithAircrafts();
        $aircrafts        = Aircraft::all();
        $aircraftAirports = AircraftAirport::all();
        $zones            = array_merge(config('zones.dangerous'), config('zones.prohibited'));

        return response()->view('map.index', compact('airports', 'aircrafts', 'zones', 'aircraftAirports'));
    }
}
