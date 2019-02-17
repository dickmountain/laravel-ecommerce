<?php

namespace App\Ecommerce\Payments;

use App\Models\PaymentMethod;

interface GatewayCustomer
{
	public function charge(PaymentMethod $paymentMethod, $amount);

	public function addCart($token);
}