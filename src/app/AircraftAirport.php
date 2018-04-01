<?php

namespace App;

/**
 * App\Models\AircraftAirport
 *
 * @property int $id
 * @property int $aircraft_id
 * @property int $airport_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Aircraft $aircraft
 * @property-read \App\Airport $airport
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftAirport whereAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftAirport whereAirportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftAirport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftAirport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftAirport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AircraftAirport extends BaseModel
{
    protected $table = 'aircraft_airport_xref';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'aircraft_id', 'airport_id'
	];

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
        return $this->belongsTo(\App\Aircraft::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function airport() {
        return $this->belongsTo(\App\Airport::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders() {
        return $this->hasMany(\App\Order::class);
    }
}
