<?php

namespace App\Ecommerce;


use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;

class Cart
{
	protected $user;
	protected $changed;
	protected $shipping;

	public function __construct(?User $user)
	{
		$this->user = $user;
	}

	public function products()
	{
		return $this->user->cart;
	}

	public function withShipping($shippingMethodId)
	{
		$this->shipping = ShippingMethod::find($shippingMethodId);

		return $this;
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

	public function sync()
	{
		$this->user->cart->each(function (ProductVariation $product) {
			$quantity = $product->getMinStock($product->pivot->quantity);

			if($quantity != $product->pivot->quantity) {
				$this->changed = true;
			}

			$product->pivot->update([
				'quantity' => $quantity
			]);
		});
	}

	public function hasChanged()
	{
		return $this->changed;
	}

	public function isEmpty()
	{
		return $this->user->cart->sum('pivot.quantity') <= 0;
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
		if ($this->shipping) {
			return $this->getSubtotal()->add($this->shipping->price);
		}

		return $this->getSubtotal();
	}
}