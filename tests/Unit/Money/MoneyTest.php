<?php

namespace Tests\Unit\Money;

use App\Ecommerce\Money;
use Tests\TestCase;
use Money\Money as BaseMoney;

class MoneyTest extends TestCase
{
    public function test_gets_raw_amount()
    {
    	$moneyValue = 1000;
    	$money = new Money($moneyValue);

    	$this->assertEquals($money->getAmount(), $moneyValue);
    }

	public function test_gets_formatted_amount()
	{
		$moneyValue = 1000;
		$money = new Money($moneyValue);

		$this->assertEquals($money->getFormattedAmount(), '$10.00');
	}

	public function test_adds_up()
	{
		$moneyValue = 1000;
		$money = new Money($moneyValue);

		$money = $money->add(new Money($moneyValue));

		$this->assertEquals($money->getAmount(), 2000);
	}

	public function test_returns_instance()
	{
		$moneyValue = 1000;
		$money = new Money($moneyValue);

		$this->assertInstanceOf(BaseMoney::class, $money->getInstance());
	}
}
