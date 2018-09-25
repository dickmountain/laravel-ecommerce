<?php

namespace Tests\Unit\Models\Users;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_hashes_password()
    {
        $user = factory(User::class)->create([
        	'password' => '12345'
        ]);

        $this->assertNotEquals($user->password, 'cats');
    }
}
