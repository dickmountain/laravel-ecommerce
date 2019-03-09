<?php

namespace Tests\Unit\Listeners;

use App\Ecommerce\Cart;
use App\Listeners\Order\EmptyCartListener;
use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class EmptyCartListenerTest extends TestCase
{

    public function test_should_clear_the_cart()
    {
	    $cart = new Cart(
		    $user = factory(User::class)->create()
	    );

	    $user->cart()->attach(
	    	$product = factory(ProductVariation::class)->create()
	    );

	    $listener = new EmptyCartListener($cart);
	    $listener->handle();

	    $this->assertEmpty($user->cart);
    }
}
