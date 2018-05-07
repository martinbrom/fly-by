<?php

namespace App\Mail\User;

use App\Mail\OrderMail;

class OrderDeleted extends OrderMail
{
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this
	        ->subject(config('app.name') . ' - Objednávka #' . $this->order->id . ' byla zrušena')
	        ->view('mail.user.order-deleted')
	        ->text('mail.user.order-deleted_plain');
    }
}
