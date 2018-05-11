<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AircraftAirport
 *
 * @package App
 * @author  Martin Brom
 * @property int $id
 * @property int $aircraft_id
 * @property int $airport_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Aircraft $aircraft
 * @property-read \App\Airport $airport
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BaseModel new()
 * @method static \Illuminate\Database\Query\Builder|\App\AircraftAirport onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftAirport whereAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftAirport whereAirportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftAirport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftAirport whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftAirport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftAirport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AircraftAirport withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\AircraftAirport withoutTrashed()
 * @mixin \Eloquent
 */
class AircraftAirport extends BaseModel
{
    use SoftDeletes;

    protected $table = 'aircraft_airport_xref';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'aircraft_id',
        'airport_id',
    ];

    /**
     * Carbon instances to be converted to dates
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        'aircraft_id' => 'required|exists:aircrafts,id',
        'airport_id' => 'required|exists:airports,id',
    ];

    /**
     * Returns a cost for flying a certain distance
     *
     * @param  int $distance
     *
     * @return int
     */
    public function getCostForDistance(int $distance): int
    {
        return $this->aircraft->getCostForDistance($distance);
    }

    /**
     * Returns a duration for a flight of certain distance in minutes
     *
     * @param  int $distance
     *
     * @return int
     */
    public function getDurationForDistance(int $distance): int
    {
        return $this->aircraft->getDurationForDistance($distance);
    }

    /**
     * Returns a distance between aircraft's home
     * airport and given airport in kilometers
     *
     * @param  Airport $airport
     *
     * @return int
     */
    public function getAirportDistance(Airport $airport)
    {
        return $this->airport->getDistance($airport);
    }

    /**
     * Checks whether aircraft is able to fly given distance
     *
     * @param  int $distance
     *
     * @return bool
     */
    public function canFly(int $distance)
    {
        return $this->aircraft->canFly($distance);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aircraft()
    {
        return $this->belongsTo(\App\Aircraft::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function airport()
    {
        return $this->belongsTo(\App\Airport::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(\App\Order::class);
    }
}
