<?php

namespace App\Mail\User;

use App\Mail\OrderMail;

class OrderConfirmed extends OrderMail
{
	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this
			->subject(config('app.name') . ' - Order #' . $this->order->id . ' is now confirmed')
			->view('mail.user.order-confirmed')
			->text('mail.user.order-confirmed_plain');
	}
}
