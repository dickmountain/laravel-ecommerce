<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Tests\TestCase;

class AddressStoreTest extends TestCase
{
	public function test_fails_if_not_authenticated()
	{
		$this->json('POST', 'api/addresses')
			->assertStatus(401);
	}

	public function test_requires_name()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/addresses')
			->assertJsonValidationErrors(['name']);
	}

	public function test_requires_address_line()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/addresses')
			->assertJsonValidationErrors(['address']);
	}

	public function test_requires_city()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/addresses')
			->assertJsonValidationErrors(['city']);
	}

	public function test_requires_postal_code()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/addresses')
			->assertJsonValidationErrors(['postal_code']);
	}

	public function test_requires_country()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/addresses')
			->assertJsonValidationErrors(['country_id']);
	}

	public function test_requires_valid_country()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/addresses', [
			'country_id' => 1
		])
			->assertJsonValidationErrors(['country_id']);
	}

	public function test_stores_address()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'POST', 'api/addresses', $post = [
			'name' => 'Max',
			'address' => 'ABC Street',
			'city' => 'Omsk',
			'postal_code' => '123456',
			'country_id' => factory(Country::class)->create()->id
		]);

		$this->assertDatabaseHas('addresses', array_merge([
			'user_id' => $user->id
		], $post));
	}

	public function test_returns_address_when_created()
	{
		$user = factory(User::class)->create();

		$response = $this->jsonAs($user, 'POST', 'api/addresses', $post = [
			'name' => 'Max',
			'address' => 'ABC Street',
			'city' => 'Omsk',
			'postal_code' => '123456',
			'country_id' => factory(Country::class)->create()->id
		]);
		$response->assertJsonFragment([
			'id' => json_decode($response->getContent())->data->id
		]);
	}
}
