<?php

namespace Tests\Feature;

use Tests\TestCase;

class TeamTest extends TestCase
{
    /**
     * Create a team.
     */
    public function test_create_team()
    {
        $response = $this->login()->post('/api/teams', [
            'name' => 'Team 1',
        ]);

        $response->assertStatus(201);
    }

    /**
     * Get all teams.
     */
    public function test_get_all_teams()
    {
        $response = $this->login()->get('/api/teams');

        $response->assertStatus(200);
    }

    /**
     * Invite a user to a team.
     */
    public function test_invite_user_to_team()
    {
        $response = $this->login()->post('/api/teams/invite', [
            'email' => 'email@teste',
            'roles' => [3]
        ]);

        $response->assertStatus(200);
    }

    /**
     * Remove user from team.
     */
    public function test_remove_user_from_team()
    {
        $response = $this->post('/api/register', [
            'name' => 'User 1',
            'email' => fake()->email,
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);
        $response->assertStatus(201);

        $user = $response->json('data.user');
        $response = $this->login()->post('/api/teams/invite', [
            'email' => $user['email'],
            'roles' => [3]
        ]);

        $response->assertStatus(200);


        $response = $this->delete('/api/teams/withdraw', [
            'user_id' => $user['id'],
        ]);

        $response->assertStatus(200);
    }
}
