<?php

namespace App\Http\Middleware\Cart;

use App\Ecommerce\Cart;
use Closure;

class Sync
{
	protected $cart;

	public function __construct(Cart $cart)
	{
		$this->cart = $cart;
	}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$this->cart->sync();

		if ($this->cart->hasChanged()) return response()->json([
			'message' => 'Some items in your cart have changed. Review these changes before placing your order.'
		], 409);

        return $next($request);
    }
}
