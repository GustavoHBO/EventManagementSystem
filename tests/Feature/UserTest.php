<?php

namespace Tests\Feature;

use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Get all users.
     */
    public function test_get_all_users()
    {
        $response = $this->login()->get('/api/users');

        $response->assertStatus(200);
    }

    /**
     * Get a user by id.
     */
    #[NoReturn] public function test_get_user_by_id()
    {
        $email = fake()->email;
        $password = '123456';
        $response = $this->post('/api/register', [
            'name' => 'User 1',
            'email' => $email,
            'password' => $password,
            'password_confirmation' => '123456',
        ]);
        $response->assertStatus(201);

        $id = $response->json('data.id');

        $response = $this->post('/api/login', [
            'email' => $email,
            'password' => $password,
        ])->json()['data'];

        $this->withHeaders([
            'authorization' => 'Bearer '.$response['access_token'],
            'Additional-Data' => $response['additional_data']
        ]);

        $this->get('/api/users/'.$id)->assertStatus(200);
    }

    public function test_get_user_by_id_not_found()
    {
        $this->login()->get('/api/users'.fake()->numberBetween(100, 200))->assertStatus(404);
    }

    /**
     * Create a user.
     */
    public function test_create_user()
    {
        $response = $this->login()->post('/api/users', [
            'name' => 'User 1',
            'email' => fake()->email,
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);
        $response->assertStatus(201);
    }

    /**
     * Update a user.
     */
    public function test_update_user()
    {
        $email = fake()->email;
        $password = '123456';
        $response = $this->post('/api/register', [
            'name' => 'User 1',
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $response->assertStatus(201);

        $id = $response->json('data.user.id');

        $response = $this->post('/api/login', [
            'email' => $email,
            'password' => $password,
        ])->json()['data'];

        $this->withHeaders([
            'authorization' => 'Bearer '.$response['access_token'],
            'Additional-Data' => $response['additional_data']
        ]);

        $response = $this->patch('/api/users/'.$id, [
            'name' => 'User 2'
        ]);
        $response->assertStatus(200);
    }

    /**
     * Fail to delete a user.
     */
    public function test_delete_user()
    {
        $response = $this->login()->delete('/api/users/'.fake()->numberBetween(100, 200));
        $response->assertStatus(Response::HTTP_NOT_IMPLEMENTED);
    }
}
