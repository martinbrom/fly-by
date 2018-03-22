<?php

namespace Tests\Unit;

use App\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test saving of valid and invalid model
     */
    public function testCreate() {
        $order = new Order();
        $this->assertFalse($order->save());

        $order = $this->getValidOrder();
        $this->assertTrue($order->save());
    }

    /**
     * Test validation of price attribute
     */
    public function testPriceValidation() {
        $order = $this->getValidOrder();
        $order->price = null;
        $this->assertFalse($order->save());

        $order->price = 'a';
        $this->assertFalse($order->save());

        $order->price = 0.5;
        $this->assertFalse($order->save());

        $order->price = -3;
        $this->assertFalse($order->save());

        $order->price = 50;
        $this->assertTrue($order->save());
    }

    /**
     * Test validation of code attribute
     */
    public function testCodeValidation() {
        $order = $this->getValidOrder();
        $order->code = null;
        $this->assertFalse($order->save());

        $order->code = str_random(33);
        $this->assertFalse($order->save());

        $order->code = str_random(32);
        $this->assertTrue($order->save());
    }

    /**
     * Test relation between order and route models
     */
    public function testOrderRouteRelation() {
        $order = $this->getValidOrder();
        $order->route()->dissociate();
        $this->assertEquals(0, $order->route()->count());
        $this->assertFalse($order->save());

        $order->route_id = 99999999;
        $this->assertFalse($order->save());

        $route = factory(\App\Route::class)->create();
        $order->route()->associate($route);
        $this->assertEquals(1, $order->route()->count());
        $this->assertTrue($order->route()->first()->is($route));
    }

    /**
     * Test relation between order and aircraft-airport models
     */
    public function testOrderAircraftAirportRelation() {
        $order = $this->getValidOrder();
        $order->aircraftAirport()->dissociate();
        $this->assertEquals(0, $order->aircraftAirport()->count());
        $this->assertFalse($order->save());

        $order->aircraft_airport_id = 99999999;
        $this->assertFalse($order->save());

        $aircraftAirport = $this->getValidAircraftAirport();
        $order->aircraftAirport()->associate($aircraftAirport);
        $this->assertEquals(1, $order->aircraftAirport()->count());
        $this->assertTrue($order->aircraftAirport()->first()->is($aircraftAirport));
    }
}