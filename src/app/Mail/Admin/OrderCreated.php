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
		return $this->view('mail.admin.order-created');
	}
}
