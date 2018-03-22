<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', function () {
    return view('home');
});

Route::resource('aircrafts', 'AircraftController', ['names' => [
    'index'   => 'aircraft.index',
    'create'  => 'aircraft.create',
    'store'   => 'aircraft.store',
    'show'    => 'aircraft.show',
    'edit'    => 'aircraft.edit',
    'update'  => 'aircraft.update',
    'destroy' => 'aircraft.destroy'
]]);

Route::resource('airports', 'AirportController', ['names' => [
    'index'   => 'airport.index',
    'create'  => 'airport.create',
    'store'   => 'airport.store',
    'show'    => 'airport.show',
    'edit'    => 'airport.edit',
    'update'  => 'airport.update',
    'destroy' => 'airport.destroy'
]]);
