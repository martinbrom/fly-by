<?php

namespace App\Listeners;

use App\Events\OrderDeleted;
use App\Mail\User\OrderDeleted as OrderDeletedUserMail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderDeletedUserNotification implements ShouldQueue
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
     * @param  OrderDeleted $event
     *
     * @return void
     */
    public function handle(OrderDeleted $event)
    {
        $order = $event->order;
        \Mail::to($order->email)
             ->queue(new OrderDeletedUserMail($order));
    }
}
