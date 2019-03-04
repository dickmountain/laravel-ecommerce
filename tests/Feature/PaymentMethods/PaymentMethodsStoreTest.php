<?php

namespace Tests\Feature\PaymentMethods;

use App\Models\User;
use Tests\TestCase;

class PaymentMethodsStoreTest extends TestCase
{
    public function test_fails_if_not_authenticated()
    {
    	$this->json('POST', 'api/payment-methods')
		    ->assertStatus(401);
    }

	public function test_requires_token()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/payment-methods')
			->assertJsonValidationErrors(['token']);
	}

	public function test_adds_card()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/payment-methods', [
			'token' => 'tok_visa'
		]);

		$this->assertDatabaseHas('payment_methods', [
			'user_id' => $user->id,
			'card_type' => 'Visa',
			'last_four' => '4242',
		]);
	}

	public function test_returns_created_card()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/payment-methods', [
			'token' => 'tok_visa'
		])->assertJsonFragment([
			'card_type' => 'Visa'
		]);
	}

	public function test_sets_created_card_as_default()
	{
		$user = factory(User::class)->create();

		$response = $this->jsonAs($user, 'POST', 'api/payment-methods', [
			'token' => 'tok_visa'
		]);

		$this->assertDatabaseHas('payment_methods', [
			'id' => json_decode($response->getContent())->data->id,
			'default' => true
		]);
	}
}
