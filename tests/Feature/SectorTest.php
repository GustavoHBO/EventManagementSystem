<?php

namespace Tests\Feature;

use App\Models\Sector;
use Tests\TestCase;

class SectorTest extends TestCase
{
    /**
     * Get all sectors.
     */
    public function test_get_all_sectors()
    {
        $response = $this->login()->get('/api/sectors');

        $response->assertStatus(200);
    }

    /**
     * Fail to get sector without permission.
     *
     */
    public function test_get_all_sectors_without_permission()
    {
        $response = $this->login()->get('/api/sectors');
        $response->assertStatus(200);

        $sectorId = $response->json('data.0.id');

        $sector = Sector::find($sectorId);

        $this->assertNotNull($sector->lots());
        $this->assertNotNull($sector->events());
        $this->assertNotNull($sector->ticketPrices());
        $this->assertNotNull($sector->tickets());
    }

    /**
     * Get a sector by id.
     */
    public function test_get_sector_by_id()
    {
        $response = $this->login()->get('/api/sectors/1');

        $response->assertStatus(200);
    }

    public function test_get_sector_by_id_not_found()
    {
        $this->login()->get('/api/sectors'.fake()->numberBetween(100, 200))->assertStatus(404);
    }

    /**
     * Create a sector.
     */
    public function test_create_sector()
    {
        $response = $this->login()->post('/api/sectors', [
            'name' => 'Sector 1',
            'capacity' => 1,
        ]);
        $response->assertStatus(201);
    }

    /**
     * Update a sector.
     */
    public function test_update_sector()
    {
        $response = $this->login()->put('/api/sectors/1', [
            'name' => 'Sector 1',
            'capacity' => 10,
        ]);

        $response->assertStatus(200);
    }

    /**
     * Delete a sector.
     */
    public function test_delete_sector()
    {
        $response = $this->login()->delete('/api/sectors/1');

        $response->assertStatus(405);
    }
}
