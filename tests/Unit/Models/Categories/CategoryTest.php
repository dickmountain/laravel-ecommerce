<?php

namespace Tests\Unit\Models\Categories;

use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function test_many_children()
    {
        $category = factory(Category::class)->create();
	    $category->children()->save(
		    factory(Category::class)->create()
	    );

	    $this->assertInstanceOf(Category::class, $category->children()->first());
    }

	public function test_fetch_only_parents()
	{
		$category = factory(Category::class)->create();
		$category->children()->save(
			factory(Category::class)->create()
		);

		$this->assertEquals(1, Category::parents()->count());
	}

	public function test_orderable_by_a_numbered_order()
	{
		$category = factory(Category::class)->create([
			'order' => 2
		]);
		$anotherCategory = factory(Category::class)->create([
			'order' => 1
		]);

		$this->assertEquals($anotherCategory->name, Category::ordered()->first()->name);
	}

	public function test_category_has_many_products()
	{
		$category = factory(Category::class)->create();

		$category->products()->save(
			factory(Product::class)->create()
		);

		$this->assertInstanceOf(Product::class, $category->products()->first());
	}
}
