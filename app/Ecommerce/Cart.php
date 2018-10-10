<?php

namespace App\Ecommerce;


use App\Models\User;

class Cart
{
	protected $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function add($products)
	{
		$this->user->cart()->syncWithoutDetaching(
			$this->getPreparedProducts($products)
		);
	}

	protected function getPreparedProducts($products)
	{
		return collect($products)->keyBy('id')->map(function ($product) {
			return [
				'quantity' => $product['quantity']
			];
		})->toArray();
	}
}