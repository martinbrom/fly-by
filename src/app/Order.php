<?php

namespace App;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $price
 * @property string $code
 * @property int $route_id
 * @property int $aircraft_airport_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\AircraftAirport $aircraftAirport
 * @property-read \App\Route $route
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereAircraftAirportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
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
        'price'
    ];

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        'price' => 'required|integer|min:0',
        'code' => 'required|max:32',
        'route_id' => 'required|exists:routes,id',
        'aircraft_airport_id' => 'required|exists:aircraft_airport_xref,id'
    ];

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