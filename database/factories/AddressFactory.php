<?php

use App\Models\Address;
use App\Models\Country;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
	    'address' => $faker->streetAddress,
	    'city' => $faker->city,
	    'postal_code' => $faker->postcode,
	    'country_id' => factory(Country::class)->create()->id,
    ];
});
