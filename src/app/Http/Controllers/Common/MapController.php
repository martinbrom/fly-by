<?php
namespace App\Http\Controllers\Common;

use App\Aircraft;
use App\Airport;
use App\Http\Controllers\CommonController;

class MapController extends CommonController
{
    /**
     * Show route planning page
     */
    public function index() {
        $airports = Airport::all();
        $aircrafts = Aircraft::all();
        return view('map.index', compact('airports', 'aircrafts'));
    }
}