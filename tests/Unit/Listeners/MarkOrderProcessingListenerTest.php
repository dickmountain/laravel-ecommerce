<?php

namespace Tests\Unit\Listeners;

use App\Events\Order\OrderPaidEvent;
use App\Listeners\Order\MarkOrderProcessingListener;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MarkOrderProcessingListenerTest extends TestCase
{
    public function test_marks_order_as_processing()
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

	    $listener = new MarkOrderProcessingListener();
	    $listener->handle($event);

	    $this->assertEquals($order->fresh()->status, Order::PROCESSING);
    }
}
