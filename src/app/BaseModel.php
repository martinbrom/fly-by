<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

/**
 * Class BaseModel
 * Parent of each App Model
 *
 * @package App
 */
abstract class BaseModel extends Model
{
    use ValidatingTrait;

	/**
	 * The name of the "latitude" column
	 *
	 * @var string
	 */
    const LATITUDE  = 'lat';

	/**
	 * The name of the "longitude" column
	 *
	 * @var string
	 */
    const LONGITUDE = 'lon';
}