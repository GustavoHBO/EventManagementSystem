<?php

namespace Tests\Feature;

use Tests\TestCase;

class LotTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetAllLots()
    {
        $response = $this->login()->get('/api/lots');

        $response->assertStatus(200);
    }

    public function testGetLotById()
    {
        $response = $this->login()->get('/api/lots/1');

        $response->assertStatus(200);
    }

    public function test_create_lot()
    {
        $response = $this->login()->post('/api/lots', [
            "event_id" => 1, // The ID of the associated event
            "name" => "Early Bird",
            "available_tickets" => 500,
            "expiration_date" => "2023-12-31" // The expiration date for the lot
        ]);

        $response->assertStatus(200);
    }

    public function test_update_lot()
    {
        $response = $this->login()->put('/api/lots/1', [
            'name' => 'Lote 1',
            'description' => 'Lote 1',
            'price' => 100.00,
            'quantity' => 10,
            'start_date' => '2021-01-01',
            'end_date' => '2021-01-31',
            'status' => 'active',
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_lot()
    {
        $response = $this->login()->post('/api/lots', [
            "event_id" => 1, // The ID of the associated event
            "name" => "Early Bird",
            "available_tickets" => 500,
            "expiration_date" => "2023-12-31" // The expiration date for the lot
        ]);

        $id = $response->json('data.id');
        $response = $this->delete("/api/lots/$id");

        $response->assertStatus(200);
    }
}
