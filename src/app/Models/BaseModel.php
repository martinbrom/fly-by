<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

/**
 * Class BaseModel
 * Parent of each App Model
 *
 * @package App\Models
 */
abstract class BaseModel extends Model
{
    use ValidatingTrait;
}