<?php

namespace Tests\Unit\Cart;

use App\Ecommerce\Cart;
use App\Ecommerce\Money;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;

class CartTest extends TestCase
{
	public function test_can_add_products_to_cart()
    {
        $cart = new Cart(
        	$user = factory(User::class)->create()
        );

        $product = factory(ProductVariation::class)->create();

	    $cart->add([
	    	['id' => $product->id, 'quantity' => 1]
	    ]);

	    $this->assertCount(1, $user->fresh()->cart);
    }

	public function test_increments_quantity_when_adding_more_product()
	{
		$product = factory(ProductVariation::class)->create();

		$cart = new Cart(
			$user = factory(User::class)->create()
		);
		$cart->add([
			['id' => $product->id, 'quantity' => 1]
		]);

		$cart = new Cart($user->fresh());
		$cart->add([
			['id' => $product->id, 'quantity' => 1]
		]);

		$this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 2);
	}

	public function test_can_update_quantities_in_cart()
	{
		$cart = new Cart(
			$user = factory(User::class)->create()
		);
		$user->cart()->attach(
			$product = factory(ProductVariation::class)->create(), [
				'quantity' => 1
			]
		);

		$cart->update($product->id, 2);

		$this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 2);
	}

	public function test_can_delete_product_from_cart()
	{
		$cart = new Cart(
			$user = factory(User::class)->create()
		);
		$user->cart()->attach(
			$product = factory(ProductVariation::class)->create(), [
				'quantity' => 1
			]
		);

		$cart->delete($product->id);

		$this->assertCount(0, $user->fresh()->cart);
	}

	public function test_can_empty_cart()
	{
		$cart = new Cart(
			$user = factory(User::class)->create()
		);
		$user->cart()->attach(
			$product = factory(ProductVariation::class)->create()
		);

		$cart->empty();

		$this->assertCount(0, $user->fresh()->cart);
	}

	public function test_can_check_cart_is_empty_of_quantities()
	{
		$cart = new Cart(
			$user = factory(User::class)->create()
		);
		$user->cart()->attach(
			$product = factory(ProductVariation::class)->create(), [
				'quantity' => 0
			]
		);

		$this->assertTrue($cart->isEmpty());
	}

	public function test_returns_money_instance_for_subtotal()
	{
		$cart = new Cart(
			$user = factory(User::class)->create()
		);

		$this->assertInstanceOf(Money::class, $cart->getSubtotal());
	}

	public function test_gets_correct_subtotal()
	{
		$cart = new Cart(
			$user = factory(User::class)->create()
		);
		$user->cart()->attach(
			$product = factory(ProductVariation::class)->create([
				'price' => 1000
			]), [
				'quantity' => 2
			]
		);

		$this->assertEquals($cart->getSubtotal()->getAmount(), 2000);
	}

	public function test_returns_money_instance_for_total()
	{
		$cart = new Cart(
			$user = factory(User::class)->create()
		);

		$this->assertInstanceOf(Money::class, $cart->getTotal());
	}

	public function test_syncs_cart_to_update_quantities()
	{
		$cart = new Cart(
			$user = factory(User::class)->create()
		);

		$product = factory(ProductVariation::class)->create();
		$anotherProduct = factory(ProductVariation::class)->create();

		$user->cart()->attach([
			$product->id => [
				'quantity' => 2
			],
			$anotherProduct->id => [
				'quantity' => 2
			]
		]);

		$cart->sync();

		$this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 0);
		$this->assertEquals($user->fresh()->cart->get(1)->pivot->quantity, 0);
	}

	public function test_syncs_cart_changed_after_syncing()
	{
		$cart = new Cart(
			$user = factory(User::class)->create()
		);

		$product = factory(ProductVariation::class)->create();
		$anotherProduct = factory(ProductVariation::class)->create();

		$user->cart()->attach([
			$product->id => [
				'quantity' => 2
			],
			$anotherProduct->id => [
				'quantity' => 0
			]
		]);

		$cart->sync();

		$this->assertTrue($cart->hasChanged());
	}

	public function test_return_correct_total_without_shipping()
	{
		$cart = new Cart(
			$user = factory(User::class)->create()
		);

		$user->cart()->attach(
			$product = factory(ProductVariation::class)->create([
				'price' => $price = 1000
			]), [
				'quantity' => $quantity = 2
			]
		);

		$this->assertEquals($cart->getTotal()->getAmount(), $price*$quantity);
	}

	public function test_returns_correct_total_with_shipping()
	{
		$cart = new Cart(
			$user = factory(User::class)->create()
		);

		$shippingMethod = factory(ShippingMethod::class)->create([
			'price' => $shippingMethodPrice = 1000
		]);

		$user->cart()->attach(
			$product = factory(ProductVariation::class)->create([
				'price' => $price = 1000
			]), [
				'quantity' => $quantity = 2
			]
		);

		$cart->withShipping($shippingMethod->id);

		$this->assertEquals($cart->getTotal()->getAmount(), $price*$quantity+$shippingMethodPrice);
	}

	public function test_returns_products_in_cart()
	{
		$cart = new Cart(
			$user = factory(User::class)->create()
		);

		$user->cart()->attach(
			$product = factory(ProductVariation::class)->create([
				'price' => $price = 1000
			]), [
				'quantity' => $quantity = 2
			]
		);

		$this->assertInstanceOf(ProductVariation::class, $cart->products()->first());
	}
}
