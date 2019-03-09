<?php

namespace Tests\Unit\Listeners;

use App\Events\Order\OrderPaymentFailedEvent;
use App\Listeners\Order\MarkOrderPaymentFailedListener;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Tests\TestCase;

class MarkOrderPaymentFailedListenerTest extends TestCase
{
    public function test_marks_order_as_payment_failed()
    {
	    $user = factory(User::class)->create();

	    $address = factory(Address::class)->create([
		    'user_id' => $user->id
	    ]);

    	$event = new OrderPaymentFailedEvent(
    		$order = factory(Order::class)->create([
    			'user_id' => $user->id,
			    'address_id' => $address->id
		    ])
	    );

    	$listener = new MarkOrderPaymentFailedListener();
	    $listener->handle($event);

	    $this->assertEquals($order->fresh()->status, Order::PAYMENT_FAILED);
    }
}
