<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\CommonController;
use App\Route;

/**
 * Class RouteController
 *
 * @package App\Http\Controllers\Common
 * @author  Martin Brom
 */
class RouteController extends CommonController
{
    /**
     * Returns all predefined routes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $routes = Route::predefined()->get();

        return response()->json($routes);
    }
}
