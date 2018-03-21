<?php

namespace App\Models;

/**
 * App\Models\Route
 *
 * @property int $id
 * @property int $distance
 * @property string $route
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Route whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Route whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Route whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Route whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Route whereUpdatedAt($value)
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
        'route'
    ];

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        'distance' => 'required|integer|min:0',
        'route' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders() {
        return $this->hasMany(\App\Models\Order::class);
    }
}
