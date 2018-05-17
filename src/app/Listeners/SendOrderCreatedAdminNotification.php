<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\Admin\OrderCreated as OrderCreatedAdminMail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderCreatedAdminNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  OrderCreated $event
     *
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        // WARNING: Email will be sent to all administrators
        // TODO: Admin settings to not receive notifications
        $users = \App\User::all();
        foreach ($users as $user) {
            \Mail::to($user->email)
                 ->queue(new OrderCreatedAdminMail($order));
        }
    }
}
