<?php

namespace Tests\Feature\PaymentMethods;

use App\Models\PaymentMethod;
use App\Models\User;
use Tests\TestCase;

class PaymentMethodIndexTest extends TestCase
{
	public function test_fails_if_not_authenticated()
	{
		$this->json('GET', 'api/payment-methods')
			->assertStatus(401);
	}

	public function test_returns_collection_of_payment_methods()
	{
		$user = factory(User::class)->create();

		$paymentMethod = factory(PaymentMethod::class)->create([
			'user_id' => $user->id
		]);

		$this->jsonAs($user, 'GET', 'api/payment-methods')
			->assertJsonFragment([
				'id' => $paymentMethod->id
			]);
	}
}
