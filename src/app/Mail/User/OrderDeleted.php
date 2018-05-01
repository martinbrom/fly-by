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
        return $this->view('mail.user.order-deleted');
    }
}
