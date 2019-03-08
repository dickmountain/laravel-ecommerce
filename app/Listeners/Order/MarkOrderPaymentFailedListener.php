<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCreatedEvent;
use App\Models\Order;

class MarkOrderPaymentFailedListener
{

    /**
     * Handle the event.
     *
     * @param  OrderCreatedEvent  $event
     * @return void
     */
    public function handle($event)
    {
		$event->order->update([
			'status' => Order::PAYMENT_FAILED
		]);
    }
}
