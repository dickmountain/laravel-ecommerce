<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductShowTest extends TestCase
{
    public function test_fails_if_product_not_found()
    {
    	$this->json('GET', 'api/products/nope')
            ->assertStatus(404);
    }

	public function test_shows_product()
	{
		$product = factory(Product::class)->create();

		$this->json('GET', "api/products/{$product->slug}")
			->assertJsonFragment([
				'id' => $product->id
			]);
	}

	public function test_shows_product_with_variations_key()
	{
		$product = factory(Product::class)->create();

		$this->json('GET', "api/products/{$product->slug}")
			->assertSeeText('variations');
	}
}
