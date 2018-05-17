<?php

namespace App;

use App\Events\OrderConfirmed;
use App\Events\OrderCreated;
use App\Events\OrderDeleted;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 *
 * @package App
 * @author  Martin Brom
 * @property int $id
 * @property int $price
 * @property int $flight_price
 * @property int|null $transport_price
 * @property int $duration
 * @property string $code
 * @property string $email
 * @property string|null $user_note
 * @property string|null $admin_note
 * @property int $route_id
 * @property int|null $aircraft_airport_id
 * @property \Carbon\Carbon|null $confirmed_at
 * @property \Carbon\Carbon|null $completed_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\AircraftAirport|null $aircraftAirport
 * @property-read \App\Route $route
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order completed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order confirmed()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BaseModel new()
 * @method static \Illuminate\Database\Query\Builder|\App\Order onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order uncompleted()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order unconfirmed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereAdminNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereAircraftAirportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereFlightPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereTransportPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUserNote($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Order withoutTrashed()
 * @mixin \Eloquent
 */
class Order extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'user_note',
    ];

    /**
     * Carbon instances to be converted to dates
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'confirmed_at',
        'completed_at',
    ];

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        'price' => 'nullable|integer|min:0',
        'flight_price' => 'nullable|integer|min:0',
        'transport_price' => 'nullable|integer|min:0',
        'duration' => 'nullable|integer|min:0',
        'code' => 'required|max:32',
        'email' => 'required|email',
        'user_note' => 'nullable|max:255',
        'admin_note' => 'nullable|max:255',
        'confirmed_at' => 'nullable|date',
        'completed_at' => 'nullable|date|after:confirmed_at',
        'route_id' => 'required|exists:routes,id',
        'aircraft_airport_id' => 'required|exists:aircraft_airport_xref,id',
    ];

    /**
     * Order constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->generateCode();
    }

    /**
     * Boots model and registers event listeners
     */
    public static function boot()
    {
        parent::boot();

        // inform owner of a new order
        // inform user that his order was created successfully
        static::created(
            function (Order $order) {
                // event(new OrderCreated($order));
            }
        );

        // recalculate prices and duration
        static::saving(
            function (Order $order) {
                $order->recalculatePrice();
                $order->recalculateDuration();
            }
        );

        // inform user that his order was cancelled
        // doesn't work for already completed orders (duh)
        static::deleting(
            function (Order $order) {
                if ($order->completed_at != null) {
                    return false;
                }
                // event(new OrderDeleted($order));

                return true;
            }
        );
    }

    /**
     * Recalculates both flight prices
     */
    public function recalculatePrice()
    {
        $this->recalculateFlightPrice();
        $this->recalculateTransportPrice();
        $this->price = $this->flight_price + $this->transport_price;
    }

    /**
     * Returns total price of flying with selected
     * aircraft from starting airport to ending airport
     */
    public function recalculateFlightPrice()
    {
        $this->flight_price = $this->aircraftAirport->getCostForDistance($this->route->distance);
    }

    /**
     * Returns total price of moving selected
     * aircraft from its current airport to the starting airport
     * and back from the ending airport
     */
    public function recalculateTransportPrice()
    {
        $distance              = $this->aircraftAirport->getAirportDistance($this->route->airportFrom)
                                 + $this->aircraftAirport->getAirportDistance($this->route->airportTo);
        $this->transport_price = $this->aircraftAirport->getCostForDistance($distance);
    }

    /**
     * Recalculates duration of flight and sets it
     */
    public function recalculateDuration()
    {
        $this->duration = $this->aircraftAirport->getDurationForDistance($this->route->distance);
    }

    /**
     * Generates 32 long unique alphanumeric order code
     */
    public function generateCode()
    {
        $this->code = str_random(32);
    }

    /**
     * Scope a query to only include confirmed orders
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConfirmed($query)
    {
        return $query->where('confirmed_at', '!=', null);
    }

    /**
     * Scope a query to only include unconfirmed orders
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnconfirmed($query)
    {
        return $query->where('confirmed_at', '=', null);
    }

    /**
     * Scope a query to only include completed orders
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed_at', '!=', null);
    }

    /**
     * Scope a query to only include uncompleted orders
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUncompleted($query)
    {
        return $query->where('completed_at', '=', null);
    }

    /**
     * Confirms given order
     */
    public function confirm()
    {
        if ($this->confirmed_at != null || $this->deleted_at != null) {
            return;
        }

        // event(new OrderConfirmed($this));
        $this->confirmed_at = \Carbon\Carbon::now();
        $this->save();
    }

    /**
     * Completes given order
     */
    public function complete()
    {
        if ($this->confirmed_at == null || $this->completed_at != null || $this->deleted_at != null) {
            return;
        }

        $this->completed_at = \Carbon\Carbon::now();
        $this->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function route()
    {
        return $this->belongsTo(\App\Route::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aircraftAirport()
    {
        return $this->belongsTo(\App\AircraftAirport::class);
    }
}
