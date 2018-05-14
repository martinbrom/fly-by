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
 * landing page or contacts page
 *
 * @package App\Http\Controllers
 */
class StaticController extends CommonController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function map()
    {
        $airports         = Airport::all();
        $aircrafts        = Aircraft::all();
        $aircraftAirports = AircraftAirport::all();
        $zones            = array_merge(config('zones.dangerous'), config('zones.prohibited'));

        return view('map.index', compact('airports', 'aircrafts', 'zones', 'aircraftAirports'));
    }
}
