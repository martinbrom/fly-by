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
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Creates a valid aircraft-airport model
     *
     * @return \App\AircraftAirport
     */
    public function getValidAircraftAirport()
    {
        $aircraftAirport = factory(\App\AircraftAirport::class)->create();
        $aircraft        = factory(\App\Aircraft::class)->create();
        $airport         = factory(\App\Airport::class)->create();

        $aircraftAirport->aircraft()->associate($aircraft);
        $aircraftAirport->airport()->associate($airport);
        $aircraftAirport->save();

        return $aircraftAirport;
    }

    /**
     * Creates a valid order model
     *
     * @return \App\Order
     */
    public function getValidOrder()
    {
        $this->withoutEvents();

        $aircraftAirport = $this->getValidAircraftAirport();
        $route           = $this->getValidRoute();
        $order           = factory(\App\Order::class)->make();

        $order->aircraftAirport()->associate($aircraftAirport);
        $order->route()->associate($route);
        $order->save();

        return $order;
    }

    /**
     * Creates a valid route model
     *
     * @param array $arguments
     *
     * @return \App\Route
     */
    public function getValidRoute(array $arguments = [])
    {
        $airport1 = factory(\App\Airport::class)->create();
        $airport2 = factory(\App\Airport::class)->create();
        $route    = factory(\App\Route::class)->make($arguments);

        $route->airportFrom()->associate($airport1);
        $route->airportTo()->associate($airport2);
        $route->save();

        return $route;
    }

    /**
     * Creates a fake valid aircraft-image model
     *
     * @return \App\AircraftImage
     */
    public function getFakeAircraftImage()
    {
        return factory(\App\AircraftImage::class, 'fake')->create();
    }
}
