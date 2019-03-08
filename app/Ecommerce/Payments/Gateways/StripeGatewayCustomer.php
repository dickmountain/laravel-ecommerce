<?php

namespace App\Ecommerce\Payments\Gateways;

use App\Ecommerce\Payments\GatewayCustomer;
use App\Models\PaymentMethod;
use Stripe\Customer as StripeCustomer;
use Stripe\Charge as StripeCharge;

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
		StripeCharge::create([
			'currency' => 'usd',
			'amount' => $amount,
			'customer' => $this->customer->id,
			'source' => $paymentMethod->provider_id
		]);
	}

	public function addCart($token)
	{
		$card = $this->customer->sources->create([
			'source' => $token
		]);

		$this->customer->default_source = $card->id;
		$this->customer->save();

		return $this->gateway->user()->paymentMethods()->create([
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