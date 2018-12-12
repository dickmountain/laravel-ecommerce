<?php

use App\Models\Address;
use App\Models\Order;
use App\Models\ShippingMethod;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'shipping_method_id' => factory(ShippingMethod::class)->create()->id
    ];
});
