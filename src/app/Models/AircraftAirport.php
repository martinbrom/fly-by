<?php

namespace App\Models;

/**
 * App\Models\AircraftAirport
 *
 * @property int $id
 * @property int $aircraft_id
 * @property int $airport_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Aircraft $aircraft
 * @property-read \App\Models\Airport $airport
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftAirport whereAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftAirport whereAirportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftAirport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftAirport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftAirport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AircraftAirport extends BaseModel
{
    protected $table = 'aircraft_airport_xref';

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        'aircraft_id' => 'required|exists:aircrafts,id',
        'airport_id' => 'required|exists:airports,id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aircraft() {
        return $this->belongsTo(\App\Models\Aircraft::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function airport() {
        return $this->belongsTo(\App\Models\Airport::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders() {
        return $this->hasMany(\App\Models\Order::class);
    }
}
