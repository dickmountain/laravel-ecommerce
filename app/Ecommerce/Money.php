<?php

namespace App\Ecommerce;

use Money\Currency;
use Money\Money as BaseMoney;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use NumberFormatter;

class Money
{
	private $money;

	public function __construct($value)
	{
		$this->money = new BaseMoney($value, new Currency('USD'));
	}

	public function getAmount()
	{
		return $this->money->getAmount();
	}

	public function getFormattedAmount()
	{
		$formatter = new IntlMoneyFormatter(
			new NumberFormatter('en_US', NumberFormatter::CURRENCY),
			new ISOCurrencies()
		);

		return $formatter->format($this->money);
	}

	public function add(Money $money)
	{
		$this->money = $this->money->add($money->getInstance());

		return $this;
	}

	public function getInstance()
	{
		return $this->money;
	}
}