<?php

namespace App;

/**
 * App\Order
 *
 * @property int $id
 * @property int $price
 * @property string $code
 * @property string $email
 * @property int $route_id
 * @property int|null $aircraft_airport_id
 * @property \Carbon\Carbon|null $confirmed_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\AircraftAirport|null $aircraftAirport
 * @property-read \App\Route $route
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order unconfirmed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereAircraftAirportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Order extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email'
    ];

	/**
	 * Carbon instances to be converted to dates
	 *
	 * @var array
	 */
    protected $dates = [
    	'created_at',
	    'updated_at',
    	'confirmed_at'
    ];

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        'price' => 'nullable|integer|min:0',
	    'duration' => 'nullable|integer|min:0',
        'code' => 'required|max:32',
	    'email' => 'required|email',
	    'confirmed_at' => 'nullable|date',
        'route_id' => 'required|exists:routes,id',
        'aircraft_airport_id' => 'required|exists:aircraft_airport_xref,id'
    ];

	/**
	 * Order constructor.
	 *
	 * @param array $attributes
	 */
    public function __construct(array $attributes = []) {
	    parent::__construct($attributes);
	    $this->generateCode();
    }

	/**
	 * Boots model and registers a 'saving' event listener
	 * to recalculate flight & transport costs on model saving
	 */
	public static function boot() {
		parent::boot();

		static::saving(function (Order $order) {
			$order->recalculatePrice();
			$order->recalculateDuration();
		});
	}

	/**
	 * Recalculates both flight prices
	 */
	public function recalculatePrice() {
	    $this->price = $this->getFlightPrice() + $this->getTransportPrice();
	}

	/**
	 * Returns total price of flying with selected
	 * aircraft from starting airport to ending airport
	 */
	public function getFlightPrice() {
	    return $this->aircraftAirport->getCostForDistance($this->route->distance);
	}

	/**
	 * Returns total price of moving selected
	 * aircraft from its current airport to the starting airport
	 * and back from the ending airport
	 */
	public function getTransportPrice() {
		$distance = $this->aircraftAirport->getAirportDistance($this->route->airportFrom)
			+ $this->aircraftAirport->getAirportDistance($this->route->airportTo);
	    return $this->aircraftAirport->getCostForDistance($distance);
	}

	/**
	 * Recalculates duration of flight and sets it
	 */
	public function recalculateDuration() {
	    $this->duration = $this->aircraftAirport->getDurationForDistance($this->route->distance);
	}

	/**
	 * Generates 32 long unique alphanumeric order code
	 */
    public function generateCode() {
    	$this->code = str_random(32);
    }

	/**
	 * Scope a query to only include unconfirmed orders
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeUnconfirmed($query) {
		return $query->where('confirmed_at', '=', null);
	}

	/**
	 * Confirms given order
	 */
    public function confirm() {
    	// TODO: Send email
    	$this->setAttribute('confirmed_at', \Carbon\Carbon::now());
    	$this->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function route() {
        return $this->belongsTo(\App\Route::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aircraftAirport() {
        return $this->belongsTo(\App\AircraftAirport::class);
    }
}
