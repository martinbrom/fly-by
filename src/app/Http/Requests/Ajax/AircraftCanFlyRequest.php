<?php

namespace App\Http\Requests\Ajax;

class AircraftCanFlyRequest extends AjaxFormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'aircraft_id' => 'bail|required|exists:aircrafts,id',
			'distance' => 'required|integer|min:0'
		];
	}
}
