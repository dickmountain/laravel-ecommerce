<?php

namespace App\Ecommerce\Payments\Gateways;

use App\Ecommerce\Payments\GatewayCustomer;
use App\Models\PaymentMethod;
use Stripe\Customer as StripeCustomer;

class StripeGatewayCustomer implements GatewayCustomer
{

	protected $gateway;
	protected $customer;

	public function __construct(StripeGateway $gateway, StripeCustomer $customer)
	{
		$this->gateway = $gateway;
		$this->customer = $customer;
	}

	public function charge(PaymentMethod $paymentMethod, $amount)
	{
		// TODO: Implement charge() method.
	}

	public function addCart($token)
	{
		$card = $this->customer->sources->create([
			'source' => $token,
			'default' => true
		]);

		$this->customer->default_source = $card->id;
		$this->customer->save();

		$this->gateway->user()->paymentMethods()->create([
			'provider_id' => $card->id,
			'card_type' => $card->brand,
			'last_four' => $card->last4,
			'default' => true
		]);
	}

	public function id()
	{
		return $this->customer->id;
	}
}