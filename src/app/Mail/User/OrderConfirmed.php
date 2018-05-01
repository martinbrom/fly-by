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
		return $this->view('mail.user.order-confirmed');
	}
}
