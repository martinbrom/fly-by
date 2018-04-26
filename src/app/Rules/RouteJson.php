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
	 * @param  mixed $route
	 * @return bool
	 */
	public function passes($attribute, $route) {
		if (is_string($route))
			$route = json_decode($route);

		if (!$route || count($route) < 1)
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
