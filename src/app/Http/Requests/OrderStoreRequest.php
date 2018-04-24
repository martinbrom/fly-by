<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
			'email' => 'required|email',
			'route' => 'required|route_json',
			'aircraft_airport_id' => 'required|exists:aircraft_airport_xref,id',
			'airport_from_id' => 'required|exists:airports,id',
			'airport_to_id' => 'required|exists:airports,id'
		];
	}
}
