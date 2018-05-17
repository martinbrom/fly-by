<?php

use Illuminate\Database\Seeder;

/**
 * Class BaseSeeder contains Faker instance
 * to generate believable data
 *
 * @author Martin Brom
 */
abstract class BaseSeeder extends Seeder
{
	protected $faker;

	function __construct() {
		$this->faker = Faker\Factory::create('cs_CZ');
	}
}
