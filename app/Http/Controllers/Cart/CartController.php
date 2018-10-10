<?php

namespace App\Http\Controllers\Cart;

use App\Ecommerce\Cart;
use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth:api']);
	}
	
	public function store(CartStoreRequest $request, Cart $cart)
	{
		$cart->add($request->products);
    }
}
