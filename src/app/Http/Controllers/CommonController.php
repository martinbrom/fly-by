<?php

namespace App\Http\Controllers;

/**
 * Class LoggedOnlyController
 * Parent of each common controller
 *
 * @package App\Http\Controllers
 */
abstract class CommonController extends Controller
{
    /**
     * CommonController constructor.
     */
    public function __construct()
    {
    }
}
