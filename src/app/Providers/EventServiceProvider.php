<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\OrderDeleted::class => [
            \App\Listeners\SendOrderDeletedUserNotification::class,
        ],
        \App\Events\OrderConfirmed::class => [
            \App\Listeners\SendOrderConfirmedUserNotification::class,
        ],
        \App\Events\OrderCreated::class => [
            \App\Listeners\SendOrderCreatedAdminNotification::class,
            \App\Listeners\SendOrderCreatedUserNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
