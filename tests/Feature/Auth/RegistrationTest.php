<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    public function test_requires_name()
    {
        $this->json('POST', 'api/auth/register')
            ->assertJsonValidationErrors(['name']);
    }

	public function test_requires_email()
	{
		$this->json('POST', 'api/auth/register')
			->assertJsonValidationErrors(['email']);
	}

	public function test_requires_valid_email()
	{
		$this->json('POST', 'api/auth/register', [
			'email' => 'www'
		])->assertJsonValidationErrors(['email']);
	}

	public function test_requires_unique_email()
	{
		$user = factory(User::class)->create([
			'password' => '12345'
		]);

		$this->json('POST', 'api/auth/register', [
			'email' => $user->email
		])->assertJsonValidationErrors(['email']);
	}

	public function test_requires_password()
	{
		$this->json('POST', 'api/auth/register')
			->assertJsonValidationErrors(['password']);
	}

	public function test_registers_user()
	{
		$this->json('POST', 'api/auth/register', [
			'name' => $name = 'Mfs',
			'email' => $email = 'mfs@gmail.com',
			'password' => 'secret'
		]);

		$this->assertDatabaseHas('users', [
			'email' => $email,
			'name' => $name
		]);
	}

	public function test_returns_user_after_registration()
	{
		$this->json('POST', 'api/auth/register', [
			'name' => 'Mfs',
			'email' => $email = 'mfs@gmail.com',
			'password' => 'secret'
		])->assertJsonFragment([
			'email' => $email
		]);

	}
}
