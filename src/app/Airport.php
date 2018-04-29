<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Malhal\Geographical\Geographical;

/**
 * App\Airport
 *
 * @property int $id
 * @property string $name
 * @property float $lat
 * @property float $lon
 * @property string|null $code
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Aircraft[] $aircrafts
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport allOther($id = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport distance($latitude, $longitude)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Query\Builder|\App\Airport onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Airport withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Airport withoutTrashed()
 * @mixin \Eloquent
 */
class Airport extends BaseModel
{
	use SoftDeletes;
	use Geographical;

	/**
	 * Decides whether distances should be calculated
	 * in miles (false) or kilometers (true)
	 *
	 * @var bool
	 */
	protected static $kilometers = true;

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'created_at', 'updated_at', 'deleted_at'
	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'lat', 'lon'
    ];

	/**
	 * Carbon instances to be converted to dates
	 *
	 * @var array
	 */
	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at'
	];

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:50',
        'code' => 'required|max:20',
        'lat' => ['required', 'regex:/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/'],
        'lon' => ['required', 'regex:/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/']
    ];

	/**
	 * Returns a distance between this airport
	 * and given airport in kilometers
	 *
	 * @param Airport $airport
	 * @return int
	 */
    public function getDistance(Airport $airport) {
        return (int) (haversineDistance($airport->lat, $airport->lon, $this->lat, $this->lon) / 1000);
    }

	/**
	 * Scope a query to include all airports but one
	 *
	 * @param   \Illuminate\Database\Eloquent\Builder $query
	 * @param   int $id
	 * @return  \Illuminate\Database\Eloquent\Builder
	 */
    public function scopeAllOther($query, $id = null) {
    	if (!isset($id)) {
    		return $query;
	    }

    	return $query->where('id', '!=', $id);
    }

	/**
	 * Returns all airports that contain an aircraft
	 */
    public static function getAllWithAircrafts() {
    	return Airport::has('aircrafts')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function aircrafts() {
        return $this->belongsToMany(\App\Aircraft::class, 'aircraft_airport_xref');
    }
}
