<?php

namespace App;

/**
 * App\AircraftImage
 *
 * @property int $id
 * @property string $path
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Aircraft[] $aircrafts
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftImage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AircraftImage extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'path',
		'description'
	];

	/**
	 * Model validation rules
	 *
	 * @var array
	 */
	protected $rules = [
		'path'        => 'required|unique:aircraft_images|max:255',
		'description' => 'max:50'
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function aircrafts() {
		return $this->hasMany(\App\Aircraft::class, 'image_id');
	}
}
