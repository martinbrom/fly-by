<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;

/**
 * Class LoggedOnlyController
 * Parent of each common controller
 *
 * @package App\Http\Controllers\Common
 */
abstract class CommonController extends Controller
{
	function __construct() {}
}
