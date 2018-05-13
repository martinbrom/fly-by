<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Mail\User\OrderConfirmed as OrderConfirmedUserMail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmedUserNotification implements ShouldQueue
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
     * @param  OrderConfirmed $event
     *
     * @return void
     */
    public function handle(OrderConfirmed $event)
    {
        $order = $event->order;
        \Mail::to($order->email)
             ->queue(new OrderConfirmedUserMail($order));
    }
}
