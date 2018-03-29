<?php

namespace App\Http\Controllers;

/**
 * Class LoggedOnlyController
 * Parent of each administration controller
 * Provides logged only access restriction
 * to the application resources
 *
 * @package App\Http\Controllers
 */
abstract class LoggedOnlyController extends Controller
{
	function __construct() {
		$this->middleware('auth');
	}
}
