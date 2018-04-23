<?php

namespace App;

/**
 * App\Models\Route
 *
 * @property int $id
 * @property int $distance
 * @property string $route
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Route whereId($value)
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
        'route'
    ];

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        'distance' => 'required|integer|min:0',
        'route' => 'required|route_json',
	    'is_predefined' => 'required|boolean'
    ];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
    protected $casts = [
    	'is_predefined' => 'boolean'
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
	 *
	 */
	private function calculateDistance() {
		// TODO: Actual distance calculation
		$this->setAttribute('distance', 1000);
	}

	/**
	 * Scope a query to only include predefined routes
	 *
	 * @param   \Illuminate\Database\Eloquent\Builder $query
	 * @return  \Illuminate\Database\Eloquent\Builder
	 */
	public function scopePredefined($query) {
		return $query->where('is_predefined', '=', 0);
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders() {
        return $this->hasMany(\App\Order::class);
    }
}
