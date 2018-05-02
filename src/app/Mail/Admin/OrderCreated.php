<?php

namespace App\Mail\Admin;

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
			->subject(config('app.name') . ' - New order created')
			->view('mail.admin.order-created')
			->text('mail.admin.order-created_plain');
	}
}
