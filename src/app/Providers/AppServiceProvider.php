<?php

namespace App\Providers;

use App\Rules\RouteJson;
use App\Rules\RouteZones;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend(
            'route_json',
            function ($attribute, $value) {
                return (new RouteJson())->passes($attribute, $value);
            }
        );

        \Validator::extend(
            'route_zones',
            function ($attribute, $value, $parameters, $validator) {
                return (new RouteZones($parameters, $validator))->passes($attribute, $value);
            }
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
