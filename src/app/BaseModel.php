<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

/**
 * Class BaseModel
 * Parent of each App Model
 *
 * @package App
 */
abstract class BaseModel extends Model
{
    use ValidatingTrait;

	/**
	 * The name of the "latitude" column
	 *
	 * @var string
	 */
    const LATITUDE  = 'lat';

	/**
	 * The name of the "longitude" column
	 *
	 * @var string
	 */
    const LONGITUDE = 'lon';

	/**
	 * Interval for selecting 'new' models in hours
	 *
	 * @var int
	 */
    const NEW_INTERVAL = 3 * 24;    // 3 days

	/**
	 * Scope a query to only include 'new' models
	 *
	 * @param   \Illuminate\Database\Eloquent\Builder $query
	 * @return  \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeNew($query) {
		$now = \Carbon\Carbon::now();
		$newIntervalCutOff = $now->subHours(self::NEW_INTERVAL);
		return $query->where('created_at', '>=', $newIntervalCutOff);
	}
}