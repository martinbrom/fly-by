<?php

namespace App\Models;

class AircraftAirport extends BaseModel
{
    protected $table = 'aircraft_airport_xref';

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        'aircraft_id' => 'required|exists:aircrafts',
        'airport_id' => 'required|exists:airports'
    ];
}
