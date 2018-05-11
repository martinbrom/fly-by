<?php

namespace App\Http\Requests\Ajax;

/**
 * Class AircraftsAtAirportRequest
 *
 * @package App\Http\Requests\Ajax
 * @author  Martin Brom
 */
class AircraftsAtAirportRequest extends AjaxFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'airport_id' => 'required|exists:airports,id',
        ];
    }
}
