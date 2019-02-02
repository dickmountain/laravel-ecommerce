<?php

namespace Tests\Unit\Models\Users;

use App\Models\Address;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_hashes_password()
    {
        $user = factory(User::class)->create([
        	'password' => '12345'
        ]);

        $this->assertNotEquals($user->password, 'cats');
    }

	public function test_has_many_cart_products()
	{
		$user = factory(User::class)->create();
		$user->cart()->attach(
			factory(ProductVariation::class)->create()
		);
		$this->assertInstanceOf(ProductVariation::class, $user->cart->first());
	}

	public function test_has_quantity_for_each_cart_product()
	{
		$user = factory(User::class)->create();
		$user->cart()->attach(
			factory(ProductVariation::class)->create(), [
				'quantity' => $quantity = 5
			]
		);
		$this->assertEquals($user->cart->first()->pivot->quantity, $quantity);
	}

	public function test_has_many_addresses()
	{
		$user = factory(User::class)->create();

		$user->addresses()->save(
			factory(Address::class)->make()
		);

		$this->assertInstanceOf(Address::class, $user->addresses->first());
	}

	public function test_has_many_orders()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id
		]);

		$this->assertInstanceOf(Order::class, $user->orders->first());
	}

	public function test_has_many_payment_methods()
	{
		$user = factory(User::class)->create();

		factory(PaymentMethod::class)->create([
			'user_id' => $user->id
		]);

		$this->assertInstanceOf(PaymentMethod::class, $user->paymentMethods->first());
	}
}
