<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AircraftAirportTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test saving of valid and invalid model
     */
    public function testCreate()
    {
        $aircraftAirport = factory(\App\AircraftAirport::class)->make();
        $this->assertFalse($aircraftAirport->save());

        $aircraftAirport = $this->getValidAircraftAirport();
        $this->assertTrue($aircraftAirport->save());
    }

    /**
     * Test propagation of canFly call onto child aircraft model
     */
    public function testCanFly()
    {
        $aircraftAirport                  = $this->getValidAircraftAirport();
        $aircraftAirport->aircraft->range = 100;
        $this->assertEquals($aircraftAirport->aircraft->canFly(200), $aircraftAirport->canFly(200));
        $this->assertEquals($aircraftAirport->aircraft->canFly(50), $aircraftAirport->canFly(50));
    }

    /**
     * Test relation between aircraft-airport and order models
     */
    public function testAircraftAirportOrderRelation()
    {
        $aircraftAirport = $this->getValidAircraftAirport();
        $order           = $this->getValidOrder();
        $order2          = $this->getValidOrder();

        $this->assertEquals(0, $aircraftAirport->orders()->count());
        $aircraftAirport->orders()->save($order);
        $this->assertEquals(1, $aircraftAirport->orders()->count());
        $aircraftAirport->orders()->save($order2);
        $this->assertEquals(2, $aircraftAirport->orders()->count());
        $this->assertTrue($aircraftAirport->orders()->first()->is($order));
    }
}
