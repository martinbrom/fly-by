<?php

namespace App\Mail\User;

use App\Mail\OrderMail;

class OrderCreated extends OrderMail
{
	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this
			->subject(config('app.name') . ' - Information about order #' . $this->order->id)
			->view('mail.user.order-created')
			->text('mail.user.order-created_plain');
	}
}
