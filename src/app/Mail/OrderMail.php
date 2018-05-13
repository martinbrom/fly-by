<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class OrderMail
 * Provides a base functionality for all order related emails
 *
 * @package App\Mail
 * @author  Martin Brom
 */
abstract class OrderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var int
     */
    public $tries = 3;

    /**
     * @var Order
     */
    public $order;

    /**
     * OrderMail constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        // TODO: From
    }
}
