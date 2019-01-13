<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Tests\TestCase;

class OrderIndexTest extends TestCase
{
    public function test_fails_if_user_is_unauthenticated()
    {
        $this->json('GET', 'api/orders')
            ->assertStatus(401);
    }

	public function test_returns_orders_collection()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$order = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id
		]);

		$this->jsonAs($user, 'GET', 'api/orders')
			->assertJsonFragment([
				'id' => $order->id
			]);
	}

	public function test_returns_ordered_collection()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id
		]);

		$order = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id
		]);

		$anotherOrder = factory(Order::class)->create([
			'user_id' => $user->id,
			'address_id' => $address->id,
			'created_at' => now()->subDay(),
		]);

		$this->jsonAs($user, 'GET', 'api/orders')
			->assertSeeInOrder([
				$order->created_at->toDateTimeString(),
				$anotherOrder->created_at->toDateTimeString(),
			]);
	}

	public function test_has_pagination()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'GET', 'api/orders')
			->assertJsonStructure([
				'links'
			]);
	}
}
