<?php

namespace Tests\Unit\Models\Products;

use App\Ecommerce\Money;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use App\Models\Stock;
use Tests\TestCase;

class ProductVariationTest extends TestCase
{
    public function test_has_one_variation_type()
    {
       $variation = factory(ProductVariation::class)->create();

		$this->assertInstanceOf(ProductVariationType::class, $variation->type);
    }

	public function test_belongs_to_product()
	{
		$variation = factory(ProductVariation::class)->create();

		$this->assertInstanceOf(Product::class, $variation->product);
	}

	public function test_returns_money_instance_for_price()
	{
		$variation = factory(ProductVariation::class)->create();

		$this->assertInstanceOf(Money::class, $variation->price);
	}

	public function test_returns_formatted_price()
	{
		$variation = factory(ProductVariation::class)->create([
			'price' => 1000
		]);

		$this->assertEquals($variation->formattedPrice, '$10.00');
	}

	public function test_returns_product_price()
	{
		$product = factory(Product::class)->create([
			'price' => 1000
		]);

		$variation = factory(ProductVariation::class)->create([
			'price' => null,
			'product_id' => $product->id
		]);

		$this->assertEquals($product->price->getAmount(), $variation->price->getAmount());
	}

	public function test_returns_own_price()
	{
		$product = factory(Product::class)->create([
			'price' => 1000
		]);

		$variation = factory(ProductVariation::class)->create([
			'price' => 2000,
			'product_id' => $product->id
		]);

		$this->assertTrue($variation->priceVaries());
	}

	public function test_has_many_stocks()
	{
		$variation = factory(ProductVariation::class)->create();

		$variation->stocks()->save(
			factory(Stock::class)->make()
		);

		$this->assertInstanceOf(Stock::class, $variation->stocks->first());
	}

	public function test_has_stock_information()
	{
		$variation = factory(ProductVariation::class)->create();

		$variation->stocks()->save(
			factory(Stock::class)->make()
		);

		$this->assertInstanceOf(ProductVariation::class, $variation->stock->first());
	}

	public function test_has_stock_count_pivot()
	{
		$variation = factory(ProductVariation::class)->create();

		$variation->stocks()->save(
			factory(Stock::class)->make([
				'quantity' => $quantity = 5
			])
		);

		$this->assertEquals($variation->stock->first()->pivot->stock, $quantity);
	}

	public function test_is_in_stock()
	{
		$variation = factory(ProductVariation::class)->create();

		$variation->stocks()->save(
			factory(Stock::class)->make()
		);

		$this->assertTrue($variation->isInStock());
	}

	public function test_get_stock_count()
	{
		$variation = factory(ProductVariation::class)->create();

		$variation->stocks()->save(
			factory(Stock::class)->make([
				'quantity' => $quantity = 5
			])
		);

		$this->assertEquals($variation->getStockCount(), $quantity);
	}
}
