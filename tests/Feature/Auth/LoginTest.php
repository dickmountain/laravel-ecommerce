<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_requires_email()
    {
	    $this->json('POST', 'api/auth/login')
            ->assertJsonValidationErrors('email');
    }

	public function test_requires_password()
	{
		$this->json('POST', 'api/auth/login')
			->assertJsonValidationErrors('password');
	}

	public function test_returns_validation_error_on_wrong_credentials()
	{
		$user = factory(User::class)->create();

		$this->json('POST', 'api/auth/login', [
			'email' => $user->email,
			'password' => '123'
		])->assertJsonValidationErrors(['email']);
	}

	public function test_returns_token()
	{
		$user = factory(User::class)->create([
			'password' => '123'
		]);

		$this->json('POST', 'api/auth/login', [
			'email' => $user->email,
			'password' => '123'
		])->assertJsonStructure([
			'meta' => [
				'token'
			]
		]);
	}

	public function test_returns_user()
	{
		$user = factory(User::class)->create([
			'password' => '123'
		]);

		$this->json('POST', 'api/auth/login', [
			'email' => $user->email,
			'password' => '123'
		])->assertJsonFragment([
			'email' => $user->email
		]);
	}
}
