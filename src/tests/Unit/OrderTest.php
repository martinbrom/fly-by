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
        $order->price = null;   // price hasn't been calculated yet
        $this->assertTrue($order->save());

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
	 * Test validation of email attribute
	 */
    public function testEmailValidation() {
    	$order = $this->getValidOrder();
    	$order->email = '';
	    $this->assertFalse($order->save());

	    $order->email = 'abcd';
	    $this->assertFalse($order->save());

	    $order->email = 'test@test.cz';
	    $this->assertTrue($order->save());
    }

	/**
	 * Test validation of confirmed_at attribute
	 */
    public function testConfirmedAtValidation() {
    	$order = $this->getValidOrder();
    	$order->confirmed_at = null;
    	$this->assertTrue($order->save());

    	$order->confirmed_at = \Carbon\Carbon::now();
    	$this->assertTrue($order->save());
    }

	/**
	 * Test for thrown exception when trying to fill
	 * confirmed_at with invalid date
	 */
    public function testConfirmedAtInvalidArgumentException() {
		$this->expectException(\InvalidArgumentException::class);
	    $order = $this->getValidOrder();
	    $order->confirmed_at = 'abcd';
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

        $route = $this->getValidRoute();
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

	/**
	 * Test confirmation of a single order
	 */
    public function testConfirmOne() {
    	$order = $this->getValidOrder();
    	$this->assertEquals(null, $order->confirmed_at);
    	$order->confirm();
    	$this->assertTrue($order->confirmed_at <= \Carbon\Carbon::now());
    }

    // TODO: Testing
    public function testRecalculateDuration() {}
    public function testRecalculateTransportPrice() {}
    public function testRecalculateFlightPrice() {}
    public function testGenerateCode() {}
}
