<?php

namespace App\Ecommerce\Payments\Gateways;

use App\Ecommerce\Payments\Gateway;
use App\Models\User;
use Stripe\Customer as StripeCustomer;

class StripeGateway implements Gateway
{
	protected $user;

	public function withUser(User $user)
	{
		$this->user = $user;

		return $this;
	}

	public function user()
	{
		return $this->user;
	}

	public function createCustomer(): StripeGatewayCustomer
	{
		if ($this->user->gateway_customer_id) {
			return $this->getCustomer();
		}

		$stripeCustomer = new StripeGatewayCustomer($this, $this->createStripeCustomer());

		$this->user->update([
			'gateway_customer_id' => $stripeCustomer->id()
		]);

		return $stripeCustomer;
	}

	public function getCustomer()
	{
		return new StripeGatewayCustomer($this, StripeCustomer::retrieve($this->user->gateway_customer_id));
	}

	protected function createStripeCustomer()
	{
		return StripeCustomer::create([
			'email' => $this->user->email
		]);
	}
}