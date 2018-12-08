<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;

class AddressShippingTest extends TestCase
{
    public function test_fails_if_user_unauthenticated()
    {
        $this->json('GET', 'api/addresses/1/shipping')
            ->assertStatus(401);
    }

	public function test_fails_if_address_cant_be_found()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'GET', 'api/addresses/1/shipping')
			->assertStatus(404);
	}

	public function test_fails_if_address_doesnt_belong_to_user()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => factory(User::class)->create()->id
		]);

		$this->jsonAs($user, 'GET', "api/addresses/$address->id/shipping")
			->assertStatus(403);
	}

	public function test_shows_shipping_methods_for_address()
	{
		$user = factory(User::class)->create();

		$address = factory(Address::class)->create([
			'user_id' => $user->id,
			'country_id' => ($country = factory(Country::class)->create())->id
		]);

		$country->shippingMethods()->save(
			$shipping = factory(ShippingMethod::class)->create()
		);

		$this->jsonAs($user, 'GET', "api/addresses/$address->id/shipping")
			->assertJsonFragment([
				'id' => $shipping->id
			]);
	}
}
