<?php

namespace App\Rules;

use App\Airport;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Validator;

class RouteZones implements Rule
{
    /**
     * @var array $data
     */
    private $data;

    /**
     * Create a new rule instance.
     *
     * @param $parameters
     * @param Validator $validator
     */
    public function __construct($parameters, Validator $validator)
    {
        $this->data = $validator->getData();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $route
     *
     * @return bool
     */
    public function passes($attribute, $route)
    {
        if (!isset($data['airport_from_id']) || !isset($data['airport_to_id'])) {
            return false;
        }

        $airportFrom = Airport::find($data['airport_from_id']);
        $airportTo   = Airport::find($data['airport_to_id']);

        // TODO: Mirek
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
