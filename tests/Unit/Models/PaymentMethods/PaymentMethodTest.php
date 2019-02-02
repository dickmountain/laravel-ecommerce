<?php

namespace Tests\Unit\Models\PaymentMethods;

use App\Models\PaymentMethod;
use App\Models\User;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
	public function test_belongs_to_user()
	{
		$paymentMethod = factory(PaymentMethod::class)->create([
			'user_id' => factory(User::class)->create()->id
		]);

		$this->assertInstanceOf(User::class, $paymentMethod->user);
	}

	public function test_sets_old_payment_methods_to_not_default_when_creating()
	{
		$user = factory(User::class)->create();

		$oldPaymentMethod = factory(PaymentMethod::class)->create([
			'default' => true,
			'user_id' => $user->id
		]);

		$paymentMethod = factory(PaymentMethod::class)->create([
			'default' => true,
			'user_id' => $user->id
		]);

		$this->assertFalse($oldPaymentMethod->fresh()->default);
	}
}
