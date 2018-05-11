<?php

namespace App\Http\Controllers\Admin;

use App\Aircraft;
use App\AircraftAirport;
use App\Airport;
use App\Http\Controllers\AdminController;
use App\Order;

/**
 * Class StaticController
 *
 * @package App\Http\Controllers\Admin
 * @author  Martin Brom
 */
class StaticController extends AdminController
{
    /**
     * Display the administration homepage
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $distinctAircraftCount = Aircraft::count();
        $unconfirmedOrderCount = Order::unconfirmed()->count();
        $completedOrderCount   = Order::completed()->count();
        $orderCount            = Order::new()->count();
        $airportCount          = Airport::count();
        $aircraftCount         = AircraftAirport::count();

        return view(
            'admin.index',
            compact(
                'distinctAircraftCount',
                'unconfirmedOrderCount',
                'completedOrderCount',
                'orderCount',
                'airportCount',
                'aircraftCount'
            )
        );
    }
}
