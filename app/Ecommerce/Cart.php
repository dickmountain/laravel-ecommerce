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

	public function update($productId, $quantity)
	{
		$this->user->cart()->updateExistingPivot($productId, [
			'quantity' => $quantity
		]);
	}

	protected function getPreparedProducts($products)
	{
		return collect($products)->keyBy('id')->map(function ($product) {
			return [
				'quantity' => $product['quantity'] + $this->getCurrentQuantity($product['id'])
			];
		})->toArray();
	}

	protected function getCurrentQuantity($productId)
	{
		if ($product = $this->user->cart->where('id', $productId)->first()) {
			return $product->pivot->quantity;
		}

		return 0;
	}

	public function delete($productId)
	{
		$this->user->cart()->detach($productId);
	}

	public function empty()
	{
		$this->user->cart()->detach();
	}

	public function isEmpty()
	{
		return $this->user->cart->sum('pivot.quantity') === 0;
	}

	public function getSubtotal()
	{
		$subtotal = $this->user->cart->sum(function ($product) {
			return $product->price->getAmount() * $product->pivot->quantity;
		});

		return new Money($subtotal);
	}

	public function getTotal()
	{
		return $this->getSubtotal();
	}
}