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

Route::get('', 'Common\StaticController@index')->name('index');
Route::get('contacts', 'Common\StaticController@contacts')->name('contacts');
Route::get('about', 'Common\StaticController@about')->name('about');
// TODO: Other static pages

// MAP ROUTES
Route::get('map', 'Common\MapController@index');

// ADMIN ROUTES
Route::prefix('admin')->group(function () {
	Route::name('admin.')->group(function () {
		Route::get('/', 'Admin\StaticController@index')->name('index');

		Route::get('aircrafts/{id}/edit-image', 'Admin\AircraftController@editImage')->name('aircrafts.edit-image');
		Route::post('aircrafts/{id}/set-image-default', 'Admin\AircraftController@defaultImage')->name('aircrafts.default-image');
		Route::post('aircrafts/{id}/store-image', 'Admin\AircraftController@storeImage')->name('aircrafts.store-image');
		Route::resource('aircrafts', 'Admin\AircraftController');

		Route::get('airports/{id}/add-aircraft', 'Admin\AirportController@addAircraft')->name('airports.add-aircraft');
		Route::resource('airports', 'Admin\AirportController');

		Route::resource('aircraft-airports', 'Admin\AircraftAirportController', [
			'only' => ['store', 'update', 'destroy']
		]);

		Route::get('orders/completed', 'Admin\OrderController@completed')->name('orders.completed');
		Route::get('orders/uncompleted', 'Admin\OrderController@uncompleted')->name('orders.uncompleted');
		Route::post('orders/{id}/complete', 'Admin\OrderController@complete')->name('orders.complete');
		Route::post('orders/{id}/confirm', 'Admin\OrderController@confirmOne')->name('orders.confirm-one');
		Route::post('orders/confirm-all', 'Admin\OrderController@confirmAll')->name('orders.confirm-all');
		Route::resource('orders', 'Admin\OrderController', [
			'except' => ['store', 'create']
		]);

		Route::get('routes/common', 'Admin\RouteController@indexCommon')->name('routes.common');
		Route::get('routes/show-common/{id}', 'Admin\RouteController@showCommon')->name('routes.show-common');
		Route::resource('routes', 'Admin\RouteController');
	});
});

// AJAX ROUTES
Route::prefix('ajax')->group(function () {
	Route::name('ajax.')->group(function () {
		Route::get('airports', 'Common\AirportController@index')->name('airports.index');
		Route::get('aircrafts', 'Common\AirportController@aircrafts')->name('airports.aircrafts');
		Route::get('routes', 'Common\RouteController@index')->name('routes.index');
	});
});

// TODO: TESTING ONLY - remove later
Route::get('orders/test-create', 'Common\OrderController@testCreate')->name('orders.test-create');
Route::resource('orders', 'Common\OrderController', [
	'only' => ['store', 'show']
]);

Route::prefix('mail')->group(function () {
	Route::name('mail.')->group(function () {
		Route::get('order-created', function () {
			return view('mail.order-created');
		});

		// TODO: Order confirmed / removed
	});
});
