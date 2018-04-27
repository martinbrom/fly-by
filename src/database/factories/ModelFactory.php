<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Airport::class, function (Faker $faker) {
    return [
        'name' => 'Airport ' . $faker->name,
        'code' => 'ICAO: ' . $faker->countryCode,
        'lon' => $faker->longitude,
        'lat' => $faker->latitude
    ];
});

$factory->define(App\Aircraft::class, function (Faker $faker) {
    return [
        'name' => 'Aircraft ' . $faker->name,
        'range' => $faker->numberBetween(100, 9999),
        'speed' => $faker->numberBetween(250, 999),
        'cost' => $faker->numberBetween(5, 30)
    ];
});

$factory->define(App\Route::class, function (Faker $faker) {
    return [
    	'is_predefined' => false,
        'distance' => $faker->numberBetween(10, 999),
        'route' => [[12.2, 13.2], [12.3, 13.3], [12.2, 13.4], [12.5, 13.2], [12.3, 13.4]]
    ];
});

$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'price' => $faker->numberBetween(100, 9999),
        'flight_price' => $faker->numberBetween(100, 9999),
        'transport_price' => $faker->numberBetween(100, 9999),
	    'email' => $faker->email,
	    'user_note' => $faker->text(100),
	    'admin_note' => $faker->text(100),
        'code' => str_random(32)
    ];
});

// TODO: Prepared for adding additional arguments to the model later on
$factory->define(App\AircraftAirport::class, function (Faker $faker) {
    return [];
});

$factory->define(App\AircraftImage::class, function (Faker $faker) {
	$upload_dir = storage_path() . '/uploads/';
	return [
		'path' => $faker->image($upload_dir, $width = 780, $height = 480)
	];
});

$factory->defineAs(App\AircraftImage::class, 'fake', function (Faker $faker) {
	$upload_dir = storage_path() . '/uploads/';
	return [
		'path' => $upload_dir . $faker->bothify('************************') . '.jpg'
	];
});
