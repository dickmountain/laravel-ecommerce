<?php

namespace App\Events\Order;

use App\Models\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class OrderPaidEvent
{
    use Dispatchable, SerializesModels;

	public $order;

	/**
	 * Create a new event instance.
	 *
	 * @param Order $order
	 */
    public function __construct(Order $order)
    {
	    $this->order = $order;
    }
}
