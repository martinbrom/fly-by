<?php
/**
 * Created by PhpStorm.
 * User: wilyxjam
 * Date: 4/27/18
 * Time: 10:21 AM
 */

namespace App\Http\Controllers\Common;


use App\Aircraft;
use App\Airport;

class MapController
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