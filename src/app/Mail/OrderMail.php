<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class OrderMail
 * Provides a base functionality for all order related emails
 *
 * @package App\Mail
 * @author Martin Brom
 */
abstract class OrderMail extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * @var Order
	 */
	public $order;

	/**
	 * OrderMail constructor.
	 *
	 * @param Order $order
	 */
	function __construct(Order $order) {
		$this->order = $order;
		// TODO: From
	}
}
