<?php

namespace App\Listeners\Order;

use App\Ecommerce\Cart;
use App\Events\Order\OrderCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmptyCartListener
{
	protected $cart;

	/**
	 * Create the event listener.
	 *
	 * @param Cart $cart
	 */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        $this->cart->empty();
    }
}
