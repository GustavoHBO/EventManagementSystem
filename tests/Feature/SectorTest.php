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
        $response = $this->login()->post('/api/events', [
            'name' => 'Event Test',
            'datetime' => '2021-12-31 23:59:59',
            'location' => 'Event Test Location',
            'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD',
            'lots' => [
                [
                    'name' => 'Lot Test',
                    'available_tickets' => 100,
                    'expiration_date' => '2021-12-31',
                    'ticket_prices' => [
                        [
                            'sector' => [
                                'name' => 'Sector Test',
                                'capacity' => 100
                            ],
                            'price' => '10.00'
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertStatus(201);
        $ticketPriceId = $response->json('data.lots.0.tickets.0.id');

        $response = $this->post('/api/orders', [
            'payment_method_id' => 1,
            'order_items' => [
                [
                    'ticket_price_id' => $ticketPriceId,
                    'quantity' => 1,
                ],
            ],
        ]);
        $response->assertStatus(201);

        $response = $this->get('/api/sectors');
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
        $response = $this->login()->post('/api/events', [
            'name' => 'Event Test',
            'datetime' => '2021-12-31 23:59:59',
            'location' => 'Event Test Location',
            'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD',
            'lots' => [
                [
                    'name' => 'Lot Test',
                    'available_tickets' => 100,
                    'expiration_date' => '2021-12-31',
                    'ticket_prices' => [
                        [
                            'sector' => [
                                'name' => 'Sector Test',
                                'capacity' => 100
                            ],
                            'price' => '10.00'
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertStatus(201);

        $sectorId = $response->json('data.lots.0.tickets.0.sector.id');
        $response = $this->get('/api/sectors/'.$sectorId);

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
        $response = $this->login()->post('/api/events', [
            'name' => 'Event Test',
            'datetime' => '2021-12-31 23:59:59',
            'location' => 'Event Test Location',
            'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD',
            'lots' => [
                [
                    'name' => 'Lot Test',
                    'available_tickets' => 100,
                    'expiration_date' => '2021-12-31',
                    'ticket_prices' => [
                        [
                            'sector' => [
                                'name' => 'Sector Test',
                                'capacity' => 100
                            ],
                            'price' => '10.00'
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertStatus(201);

        $sectorId = $response->json('data.lots.0.tickets.0.sector.id');
        $response = $this->put('/api/sectors/'.$sectorId, [
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

        $response = $this->login()->post('/api/events', [
            'name' => 'Event Test',
            'datetime' => '2021-12-31 23:59:59',
            'location' => 'Event Test Location',
            'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD',
            'lots' => [
                [
                    'name' => 'Lot Test',
                    'available_tickets' => 100,
                    'expiration_date' => '2021-12-31',
                    'ticket_prices' => [
                        [
                            'sector' => [
                                'name' => 'Sector Test',
                                'capacity' => 100
                            ],
                            'price' => '10.00'
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertStatus(201);

        $sectorId = $response->json('data.lots.0.tickets.0.sector.id');
        $response = $this->delete('/api/sectors/'.$sectorId);

        $response->assertStatus(405);
    }
}
