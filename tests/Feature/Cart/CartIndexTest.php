<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class CartIndexTest extends TestCase
{
	public function test_fails_if_unauthenticated()
	{
		$this->json('GET', 'api/cart')
			->assertStatus(401);
	}

	public function test_shows_products_in_cart()
	{
		$user = factory(User::class)->create();
		$user->cart()->sync(
			$product = factory(ProductVariation::class)->create()
		);

		$response = $this->jsonAs($user, 'GET', "api/cart")
			->assertJsonFragment([
				'id' => $product->id
			]);
	}
}
