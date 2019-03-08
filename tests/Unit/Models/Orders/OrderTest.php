<?php

namespace Tests\Unit\Models\Orders;

use App\Ecommerce\Money;
use App\Models\Address;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;

class OrderTest extends TestCase
{
	public function test_has_default_status()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$order = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id
		]);

		$this->assertEquals($order->status, Order::PENDING);
	}

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

	public function test_belongs_to_payment_method()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$order = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id
		]);

		$this->assertInstanceOf(PaymentMethod::class, $order->paymentMethod);
	}

	public function test_has_many_products()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$order = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id
		]);

		$order->products()->attach(
			factory(ProductVariation::class)->create(), [
				'quantity' => 1
			]
		);

		$this->assertInstanceOf(ProductVariation::class, $order->products->first());
	}

	public function test_has_quantity_attached_to_products()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$order = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id
		]);

		$order->products()->attach(
			factory(ProductVariation::class)->create(), [
				'quantity' => $quantity = 2
			]
		);

		$this->assertEquals($order->products->first()->pivot->quantity, $quantity);
	}

	public function test_returns_money_instance_for_subtotal()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$order = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id
		]);

		$this->assertInstanceOf(Money::class, $order->subtotal);
	}

	public function test_returns_money_instance_for_total()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$order = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id
		]);

		$this->assertInstanceOf(Money::class, $order->getTotal());
	}

	public function test_adds_shipping_to_total()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$order = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id,
			'subtotal' => 1000,
			'shipping_method_id' => factory(ShippingMethod::class)->create([
				'price' => 1000
			])
		]);

		$this->assertEquals($order->getTotal()->getAmount(), 2000);
	}
}
