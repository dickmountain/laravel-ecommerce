<?php

namespace Tests\Unit\Listeners;

use App\Ecommerce\Payments\Gateways\StripeGateway;
use App\Ecommerce\Payments\Gateways\StripeGatewayCustomer;
use App\Events\Order\OrderCreatedEvent;
use App\Events\Order\OrderPaidEvent;
use App\Events\Order\OrderPaymentFailedEvent;
use App\Exceptions\PaymentFailedException;
use App\Listeners\Order\ProcessPaymentListener;
use App\Models\Address;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

class ProcessPaymentListenerTest extends TestCase
{

    public function test_charges_chosen_payment_method()
    {
	    Event::fake();

		list($user, $paymentMethod, $order, $event) = $this->createEvent();
		list($gateway, $customer) = $this->mockFlow();

	    $customer->shouldReceive('charge')->with(
		    $order->paymentMethod, $order->getTotal()->getAmount()
	    );

	    $listener = new ProcessPaymentListener($gateway);
	    $listener->handle($event);
    }

	public function test_fires_order_paid_event()
	{
		Event::fake();

		list($user, $paymentMethod, $order, $event) = $this->createEvent();
		list($gateway, $customer) = $this->mockFlow();

		$customer->shouldReceive('charge')->with(
			$order->paymentMethod, $order->getTotal()->getAmount()
		);

		$listener = new ProcessPaymentListener($gateway);
		$listener->handle($event);

		Event::assertDispatched(OrderPaidEvent::class, function ($event) use ($order) {
			return $event->order->id === $order->id;
		});
    }

	public function test_fires_order_failed_event()
	{
		Event::fake();

		list($user, $paymentMethod, $order, $event) = $this->createEvent();
		list($gateway, $customer) = $this->mockFlow();

		$customer->shouldReceive('charge')->with(
			$order->paymentMethod, $order->getTotal()->getAmount()
		)->andThrow(PaymentFailedException::class);

		$listener = new ProcessPaymentListener($gateway);
		$listener->handle($event);

		Event::assertDispatched(OrderPaymentFailedEvent::class, function ($event) use ($order) {
			return $event->order->id === $order->id;
		});
	}

    protected function createEvent()
    {
	    $user = factory(User::class)->create();

	    $address = factory(Address::class)->create([
		    'user_id' => $user->id
	    ]);

	    $paymentMethod = factory(PaymentMethod::class)->create([
		    'user_id' => factory(User::class)->create()->id
	    ]);

	    $event = new OrderCreatedEvent(
		    $order = factory(Order::class)->create([
			    'user_id' => $user->id,
			    'address_id' => $address->id,
			    'payment_method_id' => $paymentMethod->id
		    ])
	    );

	    return [$user, $paymentMethod, $order, $event];
    }

	protected function mockFlow()
	{
		$gateway = Mockery::mock(StripeGateway::class);
		$gateway->shouldReceive('withUser')
			->andReturn($gateway)
			->shouldReceive('getCustomer')
			->andReturn(
				$customer = Mockery::mock(StripeGatewayCustomer::class)
			);

		return [$gateway, $customer];
    }
}
