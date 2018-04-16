<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Class AdminController
 * Parent of each administration controller
 * Provides logged only access restriction
 * to the application resources
 *
 * @package App\Http\Controllers\Admin
 */
abstract class AdminController extends Controller
{
	function __construct() {
		$this->middleware('auth');
	}
}
