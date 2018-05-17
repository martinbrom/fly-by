<?php

namespace App\Http\Controllers;

/**
 * Class AdminController
 * Parent of each administration controller
 * Provides logged only access restriction
 * to the application resources
 *
 * @package App\Http\Controllers
 */
abstract class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
