<?php

namespace Tests\Unit\Models\Products;

use App\Ecommerce\Money;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Stock;
use Tests\TestCase;

class ProductTest extends TestCase
{

    public function test_use_slug_for_the_route_key_name()
    {
        $product = new Product();

        $this->assertEquals($product->getRouteKeyName(), 'slug');
    }

	public function test_has_many_categories()
	{
		$product = factory(Product::class)->create();

		$product->categories()->save(
			factory(Category::class)->create()
		);

		$this->assertInstanceOf(Category::class, $product->categories->first());
	}

	public function test_has_many_variations()
	{
		$product = factory(Product::class)->create();

		$product->variations()->save(
			factory(ProductVariation::class)->create()
		);

		$this->assertInstanceOf(ProductVariation::class, $product->variations->first());
	}

	public function test_returns_money_instance_for_price()
	{
		$product = factory(Product::class)->create();

		$this->assertInstanceOf(Money::class, $product->price);
	}

	public function test_returns_formatted_price()
	{
		$product = factory(Product::class)->create([
			'price' => 1000
		]);

		$this->assertEquals($product->formattedPrice, '$10.00');
	}

	public function test_checks_stock()
	{
		$product = factory(Product::class)->create();

		$product->variations()->save(
			$variation = factory(ProductVariation::class)->create()
		);

		$variation->stocks()->save(
			factory(Stock::class)->make()
		);

		$this->assertTrue($product->isInStock());
	}

	public function test_stock_count()
	{
		$product = factory(Product::class)->create();

		$product->variations()->save(
			$variation = factory(ProductVariation::class)->create()
		);

		$variation->stocks()->save(
			factory(Stock::class)->make([
				'quantity' => $quantity = 5
			])
		);

		$this->assertEquals($product->getStockCount(), $quantity);
	}
}
