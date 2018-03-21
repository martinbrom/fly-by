<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Creates a valid aircraft-airport model
     *
     * @return \App\Models\AircraftAirport
     */
    public function getValidAircraftAirport()
    {
        $aircraftAirport = factory(\App\Models\AircraftAirport::class)->create();
        $aircraft = factory(\App\Models\Aircraft::class)->create();
        $airport = factory(\App\Models\Airport::class)->create();

        $aircraftAirport->aircraft()->associate($aircraft);
        $aircraftAirport->airport()->associate($airport);
        $aircraftAirport->save();

        return $aircraftAirport;
    }

    /**
     * Creates a valid order model
     *
     * @return \App\Models\Order
     */
    public function getValidOrder() {
        $aircraftAirport = $this->getValidAircraftAirport();
        $route = factory(\App\Models\Route::class)->create();
        $order = factory(\App\Models\Order::class)->make();

        $order->aircraftAirport()->associate($aircraftAirport);
        $order->route()->associate($route);
        $order->save();

        return $order;
    }
}
