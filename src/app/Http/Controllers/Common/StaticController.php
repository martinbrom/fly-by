<?php

namespace App\Http\Controllers\Common;

use App\Aircraft;
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
	public function index() {
		return view('index');
	}

	public function map() {
		$airports = Airport::all();
		$aircrafts = Aircraft::all();
		return view('map.index', compact('airports', 'aircrafts'));
	}
}
