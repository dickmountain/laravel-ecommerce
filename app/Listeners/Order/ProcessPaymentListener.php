<?php

namespace App\Listeners\Order;

use App\Ecommerce\Payments\Gateway;
use App\Events\Order\OrderCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessPaymentListener implements ShouldQueue
{
	protected $gateway;

	/**
	 * Create the event listener.
	 *
	 * @param Gateway $gateway
	 */
    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Handle the event.
     *
     * @param  OrderCreatedEvent  $event
     * @return void
     */
    public function handle(OrderCreatedEvent $event)
    {
		$order = $event->order;

	    $this->gateway->withUser($order->user)
		    ->getCustomer()
		    ->charge($order->paymentMethod(), $order->getTotal()->getAmount());
    }
}
