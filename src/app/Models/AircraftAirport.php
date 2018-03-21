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
        // TODO: Add validation rules for AircraftAirport model
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aircraft() {
        return $this->belongsTo('App\Models\Aircraft');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function airport() {
        return $this->belongsTo('App\Models\Airport');
    }
}
