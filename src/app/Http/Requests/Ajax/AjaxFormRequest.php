<?php

namespace App\Http\Requests\Ajax;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Class AjaxFormRequest
 * Extends basic Laravel FormRequest to implement
 * request validation for AJAX requests
 * Adds validation errors to a failed JSON response
 *
 * @package App\Http\Requests\Ajax
 * @author Martin Brom
 */
abstract class AjaxFormRequest extends FormRequest
{
	public function authorize() {}
	public function rules() {}

	protected function failedValidation(Validator $validator) {
		$errors = (new ValidationException($validator))->errors();

		throw new HttpResponseException(response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
	}
}
