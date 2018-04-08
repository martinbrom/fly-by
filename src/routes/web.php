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
})->name('index');

Route::get('/dashboard', function () {
    return view('home');
});

Route::prefix('admin')->group(function () {
	Route::name('admin.')->group(function () {
		Route::get('/', 'AdminController@index')->name('index');
		Route::resource('aircrafts', 'AircraftController');

		Route::resource('airports', 'AirportController');

		Route::resource('aircraft-airports', 'AircraftAirportController', [
			'only' => ['store', 'update', 'destroy']
		]);

		Route::post('orders/{id}/confirm', 'OrderController@confirmOne')->name('orders.confirm-one');
		Route::post('orders/confirm-all', 'OrderController@confirmAll')->name('orders.confirm-all');
		Route::resource('orders', 'OrderController', [
			'only' => ['index', 'show', 'destroy']
		]);
	});
});