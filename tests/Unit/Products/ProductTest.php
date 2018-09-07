<?php

namespace Tests\Unit\Products;

use App\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{

    public function test_use_slug_for_the_route_key_name()
    {
        $product = new Product();

        $this->assertEquals($product->getRouteKeyName(), 'slug');
    }
}