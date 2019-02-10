<?php

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'shipping_method_id' => factory(ShippingMethod::class)->create()->id,
        'payment_method_id' => factory(PaymentMethod::class)->create([
        	'user_id' => factory(User::class)->create()->id
        ])->id,
	    'subtotal' => 1000
    ];
});
