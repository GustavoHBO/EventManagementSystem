<?php

namespace Tests\Feature;

use App\Models\User;
use Str;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    public function test_register_user()
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
        $userId = $response->json('data.user.id');
        $user = User::find($userId);

        $response = $this->post('/api/login', [
            'email' => $email,
            'password' => $password,
        ])->json()['data'];

        $this->actingAs($user);

        $this->withHeaders([
            'authorization' => 'Bearer '.$response['access_token'],
            'Additional-Data' => $response['additional_data']
        ]);

        setPermissionsTeamId(null);
        $response = $this->post('/api/teams', [
            'name' => Str::words(fake()->sentence(2), 2, ''),
        ]);
        $response->assertStatus(201);
        $idTeam = $response->json('data.id');

        $response = $this->post('/api/team', [
            'team_id' => $idTeam,
        ]);
        $response->assertStatus(200);

        $response = $this->post('/api/team', [
            'team_id' => $idTeam,
        ]);
        $response->assertStatus(200);

        $actualTeam = getPermissionsTeamId();
        setPermissionsTeamId($idTeam);

        $userId = $response->json('data.user.id');

        $this->withHeaders([
            'authorization' => 'Bearer '.$response['data']['access_token'],
            'Additional-Data' => $response['data']['additional_data']
        ]);

        $response = $this->get('/api/roles');
        $response->assertStatus(200);

        $user = User::find($userId);
        $this->assertTrue($user->hasRole('super admin'));

        $response = $this->post('/api/roles', [
            'name' => fake()->word
        ]);
        $response->assertStatus(201);
        $idRole = $response->json('data.id');

        $response = $this->delete('/api/roles/'.$idRole);
        $response->assertStatus(200);

        setPermissionsTeamId($actualTeam);
    }
}
