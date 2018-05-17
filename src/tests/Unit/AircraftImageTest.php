<?php

namespace Tests\Unit;

use App\AircraftImage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AircraftImageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test saving of valid and invalid model
     */
    public function testCreate()
    {
        $aircraftImage = new AircraftImage();
        $this->assertFalse($aircraftImage->save());

        $aircraftImage = $this->getFakeAircraftImage();
        $this->assertTrue($aircraftImage->save());
    }

    /**
     * Test validation of path attribute
     */
    public function testPathValidation()
    {
        $aircraftImage       = $this->getFakeAircraftImage();
        $aircraftImage->path = '';
        $this->assertFalse($aircraftImage->save());

        $aircraftImage->path = str_random(256);
        $this->assertFalse($aircraftImage->save());

        $aircraftImage->path = str_random(255);
        $this->assertTrue($aircraftImage->save());

        $aircraftImage2       = $this->getFakeAircraftImage();
        $aircraftImage->path  = 'abcd';
        $aircraftImage2->path = 'abcd';
        $aircraftImage->save();
        $this->assertFalse($aircraftImage2->save());
    }

    /**
     * Test validation of description attribute
     */
    public function testDescriptionValidation()
    {
        $aircraftImage              = $this->getFakeAircraftImage();
        $aircraftImage->description = '';
        $this->assertTrue($aircraftImage->save());

        $aircraftImage->description = str_random(51);
        $this->assertFalse($aircraftImage->save());

        $aircraftImage->description = str_random(50);
        $this->assertTrue($aircraftImage->save());
    }

    /**
     * Test relation between aircraft-image and aircraft models
     */
    public function testAircraftImageAircraftRelation()
    {
        $aircraftImage = $this->getFakeAircraftImage();
        $aircraft      = factory(\App\Aircraft::class)->create();
        $aircraft2     = factory(\App\Aircraft::class)->create();
        $this->assertEquals(0, $aircraftImage->aircrafts()->count());

        $aircraftImage->aircrafts()->save($aircraft);
        $this->assertEquals(1, $aircraftImage->aircrafts()->count());
        $aircraftImage->aircrafts()->save($aircraft2);
        $this->assertEquals(2, $aircraftImage->aircrafts()->count());
        $this->assertTrue($aircraftImage->aircrafts()->first()->is($aircraft));
    }
}
