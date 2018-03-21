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

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Airport::class, function (Faker $faker) {
    return [
        'name' => 'Airport ' . $faker->name,
        'code' => 'ICAO: ' . $faker->countryCode,
        'lon' => $faker->longitude,
        'lat' => $faker->latitude
    ];
});

$factory->define(App\Models\Aircraft::class, function (Faker $faker) {
    return [
        'name' => 'Aircraft ' . $faker->name,
        'range' => $faker->numberBetween(100, 9999),
        'speed' => $faker->numberBetween(250, 999),
        'cost' => $faker->numberBetween(100, 9999)
    ];
});

$factory->define(App\Models\Route::class, function (Faker $faker) {
    return [
        'distance' => $faker->numberBetween(10, 999),
        'route' => 'Bod 1, Bod 2'   // TODO: Add actual generation of a (JSON ???) route
    ];
});

$factory->define(App\Models\Order::class, function (Faker $faker) {
    return [
        'price' => $faker->numberBetween(100, 9999),
        'code' => str_random(32)
    ];
});

// TODO: Prepared for adding additional arguments to the model later on
$factory->define(App\Models\AircraftAirport::class, function (Faker $faker) {
    return [];
});
