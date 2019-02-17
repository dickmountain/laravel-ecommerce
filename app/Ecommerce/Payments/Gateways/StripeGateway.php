<?php

namespace App\Ecommerce\Payments\Gateways;

use App\Ecommerce\Payments\Gateway;
use App\Models\User;

class StripeGateway implements Gateway
{
	protected $user;

	public function withUser(User $user)
	{
		$this->user = $user;

		return $this;
	}

	public function createCustomer()
	{
		return new StripeGatewayCustomer();
	}
}