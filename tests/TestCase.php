<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
        $this->user = User::find(1);
        $this->withHeaders(['Accept' => 'application/json']);
        setPermissionsTeamId(1);
    }

    /**
     * Login and return the headers.
     * @return TestCase - $this
     */
    public function login(): TestCase
    {
        $this->user = User::find(1);
        $this->withHeaders(['Accept' => 'application/json']);
        $this->actingAs($this->user);

        $response = $this->post('/api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ])->json()['data'];
        return $this->withHeaders([
            'authorization' => 'Bearer '.$response['access_token'],
            'Additional-Data' => $response['additional_data']
        ]);
    }
}
