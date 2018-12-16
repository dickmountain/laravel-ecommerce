<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
    public function test_fails_if_not_authenticated()
    {
        $this->json('POST', 'api/orders')
            ->assertStatus(401);
    }

	public function test_requires_address()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/orders')
			->assertJsonValidationErrors(['address_id']);
	}

	public function test_requires_address_that_exists()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/orders', [
			'address_id' => 1
		])->assertJsonValidationErrors(['address_id']);
	}

	public function test_requires_address_that_belongs_to_authenticated_user()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => factory(User::class)->create()->id
		]);

		$this->jsonAs($user, 'POST', 'api/orders', [
			'address_id' =>$address->id
		])->assertJsonValidationErrors(['address_id']);
	}

	public function test_requires_shipping_method()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/orders')
			->assertJsonValidationErrors(['shipping_method_id']);
	}

	public function test_requires_shipping_method_that_exists()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/orders', [
			'shipping_method_id' => 1
		])->assertJsonValidationErrors(['shipping_method_id']);
	}

	public function test_requires_valid_shipping_method()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$shippingMethod = factory(ShippingMethod::class)->create();

		$this->jsonAs($user, 'POST', 'api/orders', [
			'shipping_method_id' => $shippingMethod->id,
			'address_id' => $address->id
		])->assertJsonValidationErrors(['shipping_method_id']);
	}

	public function test_can_create_order()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$shipping = factory(ShippingMethod::class)->create();
		$shipping->countries()->attach($address->country);

		$this->jsonAs($user, 'POST', 'api/orders', [
			'shipping_method_id' => $shipping->id,
			'address_id' => $address->id
		]);

		$this->assertDatabaseHas('orders', [
			'user_id' => $user->id,
			'address_id' => $address->id,
			'shipping_method_id' => $shipping->id,
		]);
	}
}
