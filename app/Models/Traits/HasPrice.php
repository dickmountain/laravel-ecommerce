<?php

namespace App\Models\Traits;

use App\Ecommerce\Money;


trait HasPrice
{
	public function getPriceAttribute($value)
	{
		return new Money($value);
	}

	public function getFormattedPriceAttribute()
	{
		return $this->price->formatted();
	}
}