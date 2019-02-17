<?php

namespace App\Ecommerce\Payments\Gateways;

use App\Ecommerce\Payments\GatewayCustomer;
use App\Models\PaymentMethod;

class StripeGatewayCustomer implements GatewayCustomer
{

	public function charge(PaymentMethod $paymentMethod, $amount)
	{
		// TODO: Implement charge() method.
	}

	public function addCart($token)
	{
		// TODO: Implement addCart() method.
	}
}