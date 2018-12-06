<?php

namespace Tests\Unit\Models\ShippingMethods;

use App\Ecommerce\Money;
use App\Models\ShippingMethod;
use Tests\TestCase;

class ShippingMethodTest extends TestCase
{
    public function test_returns_money_instance_for_price()
    {
    	$shipping = factory(ShippingMethod::class)->create();

        $this->assertInstanceOf(Money::class, $shipping->price);
    }

	public function test_returns_formatted_price()
	{
		$shipping = factory(ShippingMethod::class)->create([
			'price' => 0
		]);

		$this->assertEquals($shipping->formattedPrice, '$0.00');
	}
}
