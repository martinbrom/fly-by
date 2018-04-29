<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Mail\OrderConfirmed as OrderConfirmedMail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmedUserNotification implements ShouldQueue
{
	/**
	 * Create the event listener.
	 */
	public function __construct() {}

	/**
	 * Handle the event.
	 *
	 * @param  OrderConfirmed  $event
	 * @return void
	 */
	public function handle(OrderConfirmed $event) {
		$order = $event->order;
		\Mail::to($order->email)
		     ->send(new OrderConfirmedMail($order));
	}
}
