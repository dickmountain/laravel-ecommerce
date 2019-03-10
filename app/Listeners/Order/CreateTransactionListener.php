<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderPaidEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTransactionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderPaidEvent  $event
     * @return void
     */
    public function handle(OrderPaidEvent $event)
    {
	    $event->order->transactions()->create([
	    	'total' => $event->order->getTotal()->getAmount()
	    ]);
    }
}
