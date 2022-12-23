<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_login_is_working()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'phone' => $user->phone,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }
}
