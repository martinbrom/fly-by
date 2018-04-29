<?php

namespace Tests\Unit;

use App\Airport;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AirportTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test saving of valid and invalid model
     */
    public function testCreate() {
        $airport = new Airport();
        $this->assertFalse($airport->save());

        $airport = factory(\App\Airport::class)->create();
        $this->assertTrue($airport->save());
    }

    /**
     * Test validation of name attribute
     */
    public function testNameValidation() {
        $airport = factory(\App\Airport::class)->create();
        $airport->name = '';
        $this->assertFalse($airport->save());

        $airport->name = str_random(51);
        $this->assertFalse($airport->save());

        $airport->name = str_random(50);
        $this->assertTrue($airport->save());
    }

    /**
     * Test validation of code attribute
     */
    public function testCodeValidation() {
        $airport = factory(\App\Airport::class)->create();
        $airport->code = '';
        $this->assertFalse($airport->save());

        $airport->code = str_random(21);
        $this->assertFalse($airport->save());

        $airport->code = str_random(20);
        $this->assertTrue($airport->save());
    }

    /**
     * Test validation of latitude attribute
     */
    public function testLatitudeValidation() {
        $airport = factory(\App\Airport::class)->create();
        $airport->lat = null;
        $this->assertFalse($airport->save());

        $airport->lat = 'a';
        $this->assertFalse($airport->save());

        $airport->lat = 90.000001;
        $this->assertFalse($airport->save());

        $airport->lat = -90.000001;
        $this->assertFalse($airport->save());

        $airport->lat = -23.123456;
        $this->assertTrue($airport->save());

        $airport->lat = 89.9999999999999;
        $this->assertTrue($airport->save());

        $airport->lat = 90;
	    $this->assertTrue($airport->save());

        $airport->lat = -90;
	    $this->assertTrue($airport->save());

        $airport->lat = 0;
	    $this->assertTrue($airport->save());

        $airport->lat = 0.123;
        $this->assertTrue($airport->save());
    }

    /**
     * Test validation of longitude attribute
     */
    public function testLongitudeValidation() {
        $airport = factory(\App\Airport::class)->create();
        $airport->lon = null;
        $this->assertFalse($airport->save());

        $airport->lon = 'a';
        $this->assertFalse($airport->save());

        $airport->lon = 180.000001;
        $this->assertFalse($airport->save());

        $airport->lon = -180.000001;
        $this->assertFalse($airport->save());

        $airport->lon = -23.123456;
        $this->assertTrue($airport->save());

        $airport->lon = 179.9999999999999;
        $this->assertTrue($airport->save());

        $airport->lon = 180;
	    $this->assertTrue($airport->save());

	    $airport->lon = -180;
	    $this->assertTrue($airport->save());

	    $airport->lon = 0;
	    $this->assertTrue($airport->save());

        $airport->lon = 0.123;
        $this->assertTrue($airport->save());
    }

	/**
	 * Test calculation of distance between two airports
	 */
    public function testGetDistance() {
	    $airport  = factory(\App\Airport::class)->create([
	    	'lat' => 1,
		    'lon' => 1
	    ]);
	    $airport2 = factory(\App\Airport::class)->create([
	    	'lat' => 1,
		    'lon' => 1.1
	    ]);
	    $this->assertEquals(11, $airport->getDistance($airport2));
	    $this->assertEquals(0,  $airport->getDistance($airport));
    }

    /**
     * Test relation between airport and aircraft models
     */
    public function testAirportAircraftRelation() {
        $airport = factory(\App\Airport::class)->create();
        $aircraft = factory(\App\Aircraft::class)->create();
        $this->assertEquals(0, $airport->aircrafts()->count());

        $airport->aircrafts()->attach($aircraft);
        $this->assertEquals(1, $airport->aircrafts()->count());
        $this->assertTrue($airport->aircrafts()->first()->is($aircraft));
    }

	/**
	 * Test query scope to include all but one airport
	 */
    public function testAllOtherQueryScope() {
        $airport  = factory(\App\Airport::class)->create();
        factory(\App\Airport::class)->create();
        factory(\App\Airport::class)->create();
        $this->assertEquals(3, count(Airport::all()));

        $airports = Airport::allOther($airport->id)->get();
        $this->assertEquals(2, count($airports));

        $airports = Airport::allOther()->get();
        $this->assertEquals(3, count($airports));
    }

	/**
	 * Test getting airports that contain an aircraft
	 */
    public function testGetAllWithAircrafts() {
    	$airport  = factory(\App\Airport::class)->create();
    	$airport2 = factory(\App\Airport::class)->create();
    	$aircraft = factory(\App\Aircraft::class)->create();
    	$aircraftAirport = factory(\App\AircraftAirport::class)->make();

    	$aircraftAirport->aircraft()->associate($aircraft);
    	$aircraftAirport->airport()->associate($airport);
    	$aircraftAirport->save();

    	$result = Airport::getAllWithAircrafts();
    	$this->assertEquals(1, $result->count());
    	$this->assertNotEquals($airport2->id, $result->first()->id);
    }
}