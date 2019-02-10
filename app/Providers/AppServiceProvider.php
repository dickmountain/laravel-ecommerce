<?php

namespace App\Providers;

use App\Ecommerce\Cart;
use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Cart::class, function ($app) {
        	if ($app->auth->user()) {
		        $app->auth->user()->load([
			        'cart.stock'
		        ]);
	        }

        	return new Cart($app->auth->user());
        });
    }
}
