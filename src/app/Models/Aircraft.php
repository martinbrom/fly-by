<?php

namespace App\Models;

/**
 * App\Models\Aircraft
 *
 * @property int $id
 * @property string $name
 * @property int $range
 * @property int $speed
 * @property int $cost
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Aircraft whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Aircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Aircraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Aircraft whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Aircraft whereRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Aircraft whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Aircraft whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Airport[] $airports
 */
class Aircraft extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cost', 'name',
    ];

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:200',
        'range' => 'required|integer|min:0',
        'speed' => 'required|integer|min:0',
        'cost' => 'required|integer|min:0'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function airports() {
        return $this->belongsToMany('App\Models\Airport', 'aircraft_airport_xref');
    }
}
