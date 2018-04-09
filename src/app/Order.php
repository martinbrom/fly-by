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
        'price' => 'required|integer|min:0',
        'code' => 'required|max:32',
	    'email' => 'required|email',
	    'confirmed_at' => 'nullable|date',
        'route_id' => 'required|exists:routes,id',
        'aircraft_airport_id' => 'nullable|exists:aircraft_airport_xref,id'
    ];

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
	 *
	 */
    public function confirm() {
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

    // TODO: Don't forget to dd extra $$$ for moving airplane to/from the flight starting/ending point
}
