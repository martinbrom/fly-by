<?php

namespace Tests\Unit;

use App\Aircraft;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AircraftTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test saving of valid and invalid model
     */
    public function testCreate() {
        $aircraft = new Aircraft();
        $this->assertFalse($aircraft->save());

        $aircraft = factory(\App\Aircraft::class)->create();
        $this->assertTrue($aircraft->save());
    }

    /**
     * Test validation of name attribute
     */
    public function testNameValidation() {
        $aircraft = factory(\App\Aircraft::class)->create();
        $aircraft->name = '';
        $this->assertFalse($aircraft->save());

        $aircraft->name = str_random(201);
        $this->assertFalse($aircraft->save());

        $aircraft->name = str_random(200);
        $this->assertTrue($aircraft->save());
    }

    /**
     * Test validation of range attribute
     */
    public function testRangeValidation() {
        $aircraft = factory(\App\Aircraft::class)->create();
        $aircraft->range = null;
        $this->assertFalse($aircraft->save());

        $aircraft->range = 'a';
        $this->assertFalse($aircraft->save());

        $aircraft->range = 0.5;
        $this->assertFalse($aircraft->save());

        $aircraft->range = -3;
        $this->assertFalse($aircraft->save());

        $aircraft->range = 50;
        $this->assertTrue($aircraft->save());
    }

    /**
     * Test validation of speed attribute
     */
    public function testSpeedValidation() {
        $aircraft = factory(\App\Aircraft::class)->create();
        $aircraft->speed = null;
        $this->assertFalse($aircraft->save());

        $aircraft->speed = 'a';
        $this->assertFalse($aircraft->save());

        $aircraft->speed = 0.5;
        $this->assertFalse($aircraft->save());

        $aircraft->speed = -3;
        $this->assertFalse($aircraft->save());

        $aircraft->speed = 50;
        $this->assertTrue($aircraft->save());
    }

    /**
     * Test validation of cost attribute
     */
    public function testCostValidation() {
        $aircraft = factory(\App\Aircraft::class)->create();
        $aircraft->cost = null;
        $this->assertFalse($aircraft->save());

        $aircraft->cost = 'a';
        $this->assertFalse($aircraft->save());

        $aircraft->cost = 0.5;
        $this->assertFalse($aircraft->save());

        $aircraft->cost = -3;
        $this->assertFalse($aircraft->save());

        $aircraft->cost = 50;
        $this->assertTrue($aircraft->save());
    }

    /**
     * Test relation between aircraft and airport models
     */
    public function testAircraftAirportRelation() {
        $aircraft = factory(\App\Aircraft::class)->create();
        $airport = factory(\App\Airport::class)->create();
        $airport2 = factory(\App\Airport::class)->create();
        $this->assertEquals(0, $aircraft->airports()->count());

        $aircraft->airports()->attach($airport);
        $this->assertEquals(1, $aircraft->airports()->count());
        $aircraft->airports()->attach($airport2);
        $this->assertEquals(2, $aircraft->airports()->count());
        $this->assertTrue($aircraft->airports()->first()->is($airport));
    }

	/**
	 * Test relation between aircraft and aircraft-image models
	 */
    public function testAircraftAircraftImageRelation() {
        $aircraft = factory(\App\Aircraft::class)->create();
        $aircraftImage = $this->getFakeAircraftImage();
        $this->assertEquals(0, $aircraft->image()->count());

        $aircraft->image()->associate($aircraftImage);
        $this->assertTrue($aircraft->image() != null);
    }

	/**
	 * Test a model function checking whether
	 * an aircraft can fly given distance
	 */
    public function testCanFly() {
        $aircraft = factory(\App\Aircraft::class)->create();
        $this->assertFalse($aircraft->canFly(99999999));
        $this->assertFalse($aircraft->canFly($aircraft->range + 1));
        $this->assertTrue($aircraft->canFly(1));
        $this->assertTrue($aircraft->canFly($aircraft->range));
    }

	/**
	 * Test calculation of price for a certain distance
	 */
    public function testGetCostForDistance() {
    	$cost     = 20;
    	$distance = 100;
    	$aircraft = factory(\App\Aircraft::class)->create([
    		'range' => $distance,
		    'cost' => $cost
	    ]);

    	$this->assertEquals(2000, $aircraft->getCostForDistance($distance));
    }

	/**
	 * Test calculation of duration for a certain distance
	 */
    public function testGetDurationForDistance() {
	    $speed    = 200;
	    $distance = 100;
	    $aircraft = factory(\App\Aircraft::class)->create([
		    'range' => $distance,
		    'speed' => $speed
	    ]);

	    $this->assertEquals(30, $aircraft->getDurationForDistance($distance));
    }
}