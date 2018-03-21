<?php

namespace App\Models;

class Aircraft extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cost', 'name',
    ];

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        // TODO: Add validation rules for Aircraft model
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function aircraftAirports() {
        return $this->belongsToMany('App\Models\AircraftAirport');
    }
}
