<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductIndexTest extends TestCase
{
    public function test_shows_collection_of_products()
    {
		$product = factory(Product::class)->create();

		$this->json('GET', 'api/products')
			->assertJsonFragment([
				'id' => $product->id
			]);
    }

	public function test_has_paginated_data()
	{

		$this->json('GET', 'api/products')
			->assertJsonStructure([
				'links'
			]);
	}
}
