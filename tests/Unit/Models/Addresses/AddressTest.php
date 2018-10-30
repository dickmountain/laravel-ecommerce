<?php

namespace Tests\Unit\Models\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function test_has_one_country()
    {
    	$address = factory(Address::class)->create([
    		'user_id' => factory(User::class)->create()->id
	    ]);

        $this->assertInstanceOf(Country::class, $address->country);
    }

	public function test_belongs_to_user()
	{
		$address = factory(Address::class)->create([
			'user_id' => factory(User::class)->create()->id
		]);

		$this->assertInstanceOf(User::class, $address->user);
	}

	public function test_sets_all_addresses_to_not_default()
	{
		$user = factory(User::class)->create();

		$oldAddress = factory(Address::class)->create([
			'default' => true,
			'user_id' => $user->id
		]);

		$address = factory(Address::class)->create([
			'default' => true,
			'user_id' => $user->id
		]);

		$this->assertFalse($oldAddress->fresh()->default);
	}
}
