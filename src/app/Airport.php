<?php

namespace App;

/**
 * App\Models\Airport
 *
 * @property int $id
 * @property string $name
 * @property float $lon
 * @property float $lat
 * @property string|null $code
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Airport whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Aircraft[] $aircrafts
 */
class Airport extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name',
    ];

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:50',
        'code' => 'required|max:20',
        'lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
        'lon' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0]?[0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function aircrafts() {
        return $this->belongsToMany(\App\Aircraft::class, 'aircraft_airport_xref');
    }
}
