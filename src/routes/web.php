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

// AUTH ROUTES
Route::get('login', 'Auth\LoginController@showLoginForm')
    ->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')
    ->name('logout');

// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
    ->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
    ->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')
    ->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('', 'Common\StaticController@index')
    ->name('index');
Route::get('map', 'Common\StaticController@map')
    ->name('map');

// ADMIN ROUTES
Route::prefix('admin')->group(function () {
    Route::name('admin.')->group(function () {
        Route::get('/', 'Admin\StaticController@index')
            ->name('index');

        Route::get('aircrafts/{id}/edit-image', 'Admin\AircraftController@editImage')
            ->name('aircrafts.edit-image');
        Route::post('aircrafts/{id}/set-image-default', 'Admin\AircraftController@defaultImage')
            ->name('aircrafts.default-image');
        Route::post('aircrafts/{id}/store-image', 'Admin\AircraftController@storeImage')
            ->name('aircrafts.store-image');
        Route::resource('aircrafts', 'Admin\AircraftController');

        Route::get('airports/{id}/add-aircraft', 'Admin\AirportController@addAircraft')
            ->name('airports.add-aircraft');
        Route::resource('airports', 'Admin\AirportController');

        Route::resource('aircraft-airports', 'Admin\AircraftAirportController', [
            'only' => ['store', 'update', 'destroy'],
        ]);

        Route::get('orders/completed', 'Admin\OrderController@completed')
            ->name('orders.completed');
        Route::get('orders/uncompleted', 'Admin\OrderController@uncompleted')
            ->name('orders.uncompleted');
        Route::post('orders/{id}/complete', 'Admin\OrderController@complete')
            ->name('orders.complete');
        Route::post('orders/{id}/confirm', 'Admin\OrderController@confirmOne')
            ->name('orders.confirm-one');
        Route::post('orders/confirm-all', 'Admin\OrderController@confirmAll')
            ->name('orders.confirm-all');
        Route::resource('orders', 'Admin\OrderController', [
            'except' => ['store', 'create'],
        ]);

        Route::get('routes/common', 'Admin\RouteController@indexCommon')
            ->name('routes.common');
        Route::get('routes/show-common/{id}', 'Admin\RouteController@showCommon')
            ->name('routes.show-common');
        Route::resource('routes', 'Admin\RouteController');
    });
});

// AJAX ROUTES
Route::prefix('ajax')->group(function () {
    Route::name('ajax.')->group(function () {
        Route::get('airports', 'Common\AirportController@index')
            ->name('airports.index');
        Route::get('aircrafts', 'Common\AirportController@aircrafts')
            ->name('airports.aircrafts');
        Route::get('other-aircrafts', 'Common\AirportController@otherAircrafts')
            ->name('airports.other-aircrafts');
        Route::get('routes', 'Common\RouteController@index')
            ->name('routes.index');
        Route::get('aircraft-airports/{id}', 'Common\AircraftAirportController@show')
            ->name('aircraft-airports.show');
    });
});

// TODO: TESTING ONLY - remove later
Route::get('orders/test-create', 'Common\OrderController@testCreate')
    ->name('orders.test-create');

Route::get('orders/{code}/pdf', 'Common\OrderController@downloadCoupon')
    ->name('orders.download-coupon');
Route::resource('orders', 'Common\OrderController', [
    'only' => ['store', 'show'],
]);
