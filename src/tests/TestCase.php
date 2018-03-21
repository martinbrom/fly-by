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
     * Create valid aircraft model
     *
     * @return \App\Models\Aircraft
     */
    public function getValidAircraft() {
        $aircraft = factory(\App\Models\Aircraft::class)->create();
        $aircraft->save();

        return $aircraft;
    }

    /**
     * Create valid airport model
     *
     * @return \App\Models\Airport
     */
    public function getValidAirport() {
        $airport = factory(\App\Models\Airport::class)->create();
        $airport->save();

        return $airport;
    }

    /**
     * @return \App\Models\AircraftAirport
     */
    public function getValidAircraftAirport()
    {
        $aircraftAirport = factory(\App\Models\AircraftAirport::class)->create();
        $aircraft = $this->getValidAircraft();
        $airport = $this->getValidAirport();

        $aircraftAirport->aircraft()->associate($aircraft);
        $aircraftAirport->airport()->associate($airport);

        return $aircraftAirport;
    }
}
