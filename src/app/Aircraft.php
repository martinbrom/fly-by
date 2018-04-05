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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Airport[] $airports
 * @property-read \App\AircraftImage|null $image
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Aircraft extends BaseModel
{
	use SoftDeletes;

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
		'image_id' => 'exists:aircraft_images,id',
		'name'     => 'required|max:200',
		'range'    => 'required|integer|min:0',
		'speed'    => 'required|integer|min:0',
		'cost'     => 'required|integer|min:0'
	];

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
