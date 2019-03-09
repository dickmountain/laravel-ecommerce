<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCreatedEvent;
use App\Models\Order;

class MarkOrderProcessingListener
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
			'status' => Order::PROCESSING
		]);
    }
}
