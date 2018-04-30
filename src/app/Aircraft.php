<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Aircraft
 *
 * @property int $id
 * @property int|null $image_id
 * @property string $name
 * @property int $range
 * @property int $speed
 * @property int $cost
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Airport[] $airports
 * @property-read \App\AircraftImage|null $image
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Aircraft onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Aircraft withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Aircraft withoutTrashed()
 * @mixin \Eloquent
 */
class Aircraft extends BaseModel
{
	use SoftDeletes;

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
		'cost',
		'name',
		'range',
		'speed'
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
		'image_id' => 'nullable|exists:aircraft_images,id',
		'name'     => 'required|max:200',
		'range'    => 'required|integer|min:0',
		'speed'    => 'required|integer|min:0',
		'cost'     => 'required|integer|min:0'
	];

	/**
	 * Checks whether this aircraft is able
	 * to fly a given distance
	 *
	 * @param   int $distance
	 * @return  bool
	 */
	public function canFly(int $distance) {
		return $this->range >= $distance;
	}

	/**
	 * Returns a cost for flying a certain distance in CZK
	 *
	 * @param   int $distance
	 * @return  int
	 */
	public function getCostForDistance(int $distance): int {
		return $this->cost * $distance;
	}

	/**
	 * Returns a duration for a flight of certain distance in seconds
	 *
	 * @param int $distance
	 * @return int
	 */
	public function getDurationForDistance(int $distance): int {
		return (int) (3600 * $distance / $this->speed);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function airports() {
		return $this->belongsToMany(\App\Airport::class, 'aircraft_airport_xref');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function image() {
		return $this->belongsTo(\App\AircraftImage::class);
	}
}
