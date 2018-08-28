<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryIndexTest extends TestCase
{
    public function test_returns_a_collection_of_categories()
    {
       $category = factory(Category::class)->create();

       $this->json('GET', 'api/categories')->assertJsonFragment([
       	    'slug' => $category->slug
       ]);
    }

	public function test_returns_only_parent_categories()
	{
		$category = factory(Category::class)->create();

		$category->children()->save(
			factory(Category::class)->create()
		);

		$this->json('GET', 'api/categories')->assertJsonCount(1, 'data');
	}

	public function test_returns_categories_ordered_by_order()
	{
		$category = factory(Category::class)->create([
			'order' => 2
		]);
		$anotherCategory = factory(Category::class)->create([
			'order' => 1
		]);

		$this->json('GET', 'api/categories')->assertSeeInOrder([
			$anotherCategory->slug, $category->slug
		]);
	}
}
