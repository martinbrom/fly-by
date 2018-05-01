<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\User\OrderCreated as OrderCreatedUserMail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderCreatedUserNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     *
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event) {
    	$order = $event->order;
    	\Mail::to($order->email)
		    ->send(new OrderCreatedUserMail($order));
    }
}
