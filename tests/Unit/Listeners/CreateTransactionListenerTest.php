<?php

namespace Tests\Unit\Listeners;

use App\Events\Order\OrderPaidEvent;
use App\Events\Order\OrderPaymentFailedEvent;
use App\Listeners\Order\CreateTransactionListener;
use App\Listeners\Order\MarkOrderPaymentFailedListener;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Tests\TestCase;

class CreateTransactionListenerTest extends TestCase
{
    public function test_creates_transaction()
    {
	    $user = factory(User::class)->create();

	    $address = factory(Address::class)->create([
		    'user_id' => $user->id
	    ]);

    	$event = new OrderPaidEvent(
    		$order = factory(Order::class)->create([
    			'user_id' => $user->id,
			    'address_id' => $address->id
		    ])
	    );

    	$listener = new CreateTransactionListener();
	    $listener->handle($event);

	    $this->assertDatabaseHas('transactions', [
	    	'order_id' => $order->id,
	    	'total' => $order->getTotal()->getAmount(),
	    ]);
    }
}
