<?php

namespace App;

use App\Http\Requests\AircraftImageStoreRequest;

/**
 * Class AircraftImage
 *
 * @package App
 * @author  Martin Brom
 * @property int $id
 * @property string $path
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Aircraft[] $aircrafts
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BaseModel new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftImage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AircraftImage extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path',
        'description',
    ];

    /**
     * Model validation rules
     *
     * @var array
     */
    protected $rules = [
        'path' => 'required|unique:aircraft_images|max:255',
        'description' => 'max:50',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aircrafts()
    {
        return $this->hasMany(\App\Aircraft::class, 'image_id');
    }

    /**
     * @param   AircraftImageStoreRequest $request
     *
     * @return  bool
     */
    public function saveFromRequest(AircraftImageStoreRequest $request): bool
    {
        $this->description = $request->input('description');
        $this->path        = substr($request->file('image')->store('public/aircraft-images'), 7);

        return $this->save();
    }
}
