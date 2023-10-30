<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    /**
     * Test if the user can log in.
     * @return void
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->create();
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
    }

    /**
     * Test if the user can log out.
     * @return void
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);

        $this->post('/api/logout', [
            'token' => $response->json()['data']['access_token']
        ]);
        $response->assertStatus(200);
    }

    public function test_user_logout_all_devices(): void
    {
        $user = User::factory()->create();
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);

        $this->post('/api/logout', [
            'token' => $response->json()['data']['access_token'],
            'all_devices' => true
        ]);
        $response->assertStatus(200);
    }

    /**
     * Test if the user can get the authenticated user.
     * @return void
     */
    public function test_user_can_get_authenticated_user(): void
    {
        $response = $this->login()->get('/api/user');
        $response->assertStatus(200);
    }
}
