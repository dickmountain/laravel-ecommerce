<?php

namespace App\Http\Controllers\Orders;

use App\Ecommerce\Cart;
use App\Http\Requests\Orders\OrderStoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
	protected $cart;

	public function __construct()
	{
		$this->middleware(['auth:api']);
	}

	public function store(OrderStoreRequest $request, Cart $cart)
	{
		$order = $this->createOrder($request, $cart);
    }

    protected function createOrder(Request $request, Cart $cart)
    {
	    return $request->user()->orders()->create(
		    array_merge($request->only(['address_id', 'shipping_method_id']), [
		    	'subtotal' => $cart->getSubtotal()->getAmount()
		    ])
	    );
    }
}
