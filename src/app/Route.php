<?php

namespace App;

/**
 * App\Route
 *
 * @property int $id
 * @property int $airport_from_id
 * @property int $airport_to_id
 * @property int $distance
 * @property array $route
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property bool $is_predefined
 * @property-read \App\Airport $airportFrom
 * @property-read \App\Airport $airportTo
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route predefined()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereAirportFromId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereAirportToId($value)
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
		'route', 'airport_from_id', 'airport_to_id'
	];

	/**
	 * Model validation rules
	 *
	 * @var array
	 */
	// TODO: Too short routes validation
	protected $rules = [
		'airport_from_id' => 'required|exists:airports,id',
		'airport_to_id' => 'required|exists:airports,id',
		'distance' => 'nullable|integer|min:0',
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
		'route' => 'array'
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
	}

	/**
	 * Boots model and registers a 'saving' event listener
	 * to recalculate route distance on model saving
	 */
	public static function boot() {
		parent::boot();

		static::saving(function (Route $route) {
			$route->recalculateTotalDistance();
		});
	}

	/**
	 * Recalculates total distance needed for the
	 * aircraft to cover and sets it as the route distance
	 */
	public function recalculateTotalDistance() {
		$distance = 0;
		$airportFrom = $this->airportFrom;
		$airportTo   = $this->airportTo;
		$points = $this->route;

		if (is_string($points)) {
			$points = json_decode($points);
		}

		array_unshift($points, [$airportFrom->lat, $airportFrom->lon]);
		$points []= [$airportTo->lat, $airportTo->lon];

		$pointsCount = count($points);
		for ($i = 0; $i < $pointsCount - 1; $i++) {
			$distance += haversineDistance($points[$i][0], $points[$i][1], $points[$i+1][0], $points[$i+1][1]);
		}

		$this->distance = (int) ($distance / 1000); // to kilometers
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
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function airportFrom() {
	    return $this->belongsTo(\App\Airport::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function airportTo() {
		return $this->belongsTo(\App\Airport::class);
	}
}
