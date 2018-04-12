<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RouteJson implements Rule
{
	/**
	 * Create a new rule instance.
	 */
	public function __construct() {}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string $attribute
	 * @param  mixed $value
	 * @return bool
	 */
	public function passes($attribute, $value) {
		if (!is_string($value))
			return false;

		$route = json_decode($value);

		if (!$route)
			return false;

		foreach ($route as $point) {
			if (!isset($point[0]) || !isset($point[1]))
				return false;

			$lat = preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $point[0]);
			$lon = preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $point[1]);

			if (!$lat || !$lon)
				return false;
		}

		return true;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message() {
		return 'The validation error message.';
	}
}
