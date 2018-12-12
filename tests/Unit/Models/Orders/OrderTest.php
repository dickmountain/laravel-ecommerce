<?php

namespace Tests\Unit\Models\Orders;

use App\Models\Address;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;

class OrderTest extends TestCase
{
	public function test_belongs_to_user()
    {
    	$user = factory(User::class)->create();

	    $address = factory(Address::class)->create([
	    	'user_id' => $user->id
	    ]);

		$order = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id
		]);

		$this->assertInstanceOf(User::class, $order->user);
    }

	public function test_belongs_to_address()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$order = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id
		]);

		$this->assertInstanceOf(Address::class, $order->address);
	}

	public function test_belongs_to_shipping_method()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$order = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id
		]);

		$this->assertInstanceOf(ShippingMethod::class, $order->shippingMethod);
	}
}
