<?php

namespace App;

/**
 * App\Route
 *
 * @property int $id
 * @property int $distance
 * @property string $route
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property bool $is_predefined
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route predefined()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereIsPredefined($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Route extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'route',
	];

	/**
	 * Model validation rules
	 *
	 * @var array
	 */
	protected $rules = [
		'distance' => 'required|integer|min:0',
		'route' => 'required|route_json',
		'is_predefined' => 'required|boolean',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'is_predefined' => 'boolean',
	];

	/**
	 * Route constructor.
	 *
	 * @param array $attributes
	 */
	public function __construct(array $attributes = []) {
		parent::__construct($attributes);

		if (!isset($this->is_predefined)) {
			$this->is_predefined = 0;
		}

		$this->calculateDistance();
	}

	/**
	 * Calculates the total route distance using
	 * the haversine great circle distance formula
	 */
	private function calculateDistance() {
		$distance        = 0;
		$coordinates     = json_decode($this->route, TRUE);
		$coordinateCount = count($coordinates);

		for ($i = 0; $i < $coordinateCount - 1; $i++) {
			$latFrom = $coordinates[$i][0];
			$lonFrom = $coordinates[$i][1];
			$latTo   = $coordinates[$i + 1][0];
			$lonTo   = $coordinates[$i + 1][1];
			$distance += $this->haversineDistance($latFrom, $lonFrom, $latTo, $lonTo);
		}

		$this->setAttribute('distance', (int) ($distance / 1000));
	}

	/**
	 * Scope a query to only include predefined routes
	 *
	 * @param   \Illuminate\Database\Eloquent\Builder $query
	 * @return  \Illuminate\Database\Eloquent\Builder
	 */
	public function scopePredefined($query) {
		return $query->where('is_predefined', '=', 1);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function orders() {
		return $this->hasMany(\App\Order::class);
	}

	/**
	 * @param $latitudeFrom
	 * @param $longitudeFrom
	 * @param $latitudeTo
	 * @param $longitudeTo
	 * @return int
	 */
	private function haversineDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo) {
		$earthRadius = 6371000; // in meters

		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo   = deg2rad($latitudeTo);
		$lonTo   = deg2rad($longitudeTo);

		$latDelta = $latTo - $latFrom;
		$lonDelta = $lonTo - $lonFrom;

		$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

		return $angle * $earthRadius;
	}
}
