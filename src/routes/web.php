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

Route::get('admin', 'AdminController@index')->name('admin.index');

Route::resource('aircrafts', 'AircraftController');
Route::resource('airports', 'AirportController');

Route::get('manage-aircrafts', 'AircraftAirportController@index')->name('aircraft-airports.index');
Route::post('add-aircraft-to-airport', 'AircraftAirportController@add')->name('aircraft-airports.add');
Route::post('remove-aircraft-from-airport', 'AircraftAirportController@remove')->name('aircraft-airports.remove');
