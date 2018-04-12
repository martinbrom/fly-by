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

// Auth Routes
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('', 'StaticController@index')->name('index');
Route::get('contacts', 'StaticController@contacts')->name('contacts');
Route::get('about', 'StaticController@about')->name('about');
// TODO: Other static pages

Route::prefix('admin')->group(function () {
	Route::name('admin.')->group(function () {
		Route::get('/', 'AdminController@index')->name('index');

		Route::get('aircrafts/{id}/edit-image', 'AircraftController@editImage')->name('aircrafts.edit-image');
		Route::post('aircrafts/{id}/set-image-default', 'AircraftController@defaultImage')->name('aircrafts.default-image');
		Route::post('aircrafts/{id}/store-image', 'AircraftController@storeImage')->name('aircrafts.store-image');
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