<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class MeTest extends TestCase
{
    public function test_fails_if_user_not_authenticated()
    {
        $this->json('GET', 'api/auth/me')
            ->assertStatus(401);
    }

	public function test_returns_user_details()
	{
		$user = factory(User::class)->create();

		$this->jsonAs($user, 'GET', 'api/auth/me')
			->assertJsonFragment([
				'email' => $user->email
			]);
	}
}
