<?php

namespace Tests\Feature;

use Tests\TestCase;

class EventTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_event(): void
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
    }

    /**
     * Test the get events.
     */
    public function test_get_events(): void
    {
        $response = $this->login()->get('/api/events');
        $response->assertStatus(200);
    }

    /**
     * Test the get event by id.
     */
    public function test_get_event_by_id(): void
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
        $id = $response->json('data.id');

        $response = $this->get("/api/events/$id");
        $response->assertStatus(200);
    }

    /**
     * Test the update event.
     */
    public function test_update_event(): void
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
        $id = $response->json('data.id');

        $response = $this->put("/api/events/$id", [
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
        $response->assertStatus(500);
    }

    /**
     * Test the delete event.
     */
    public function test_delete_event(): void
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
        $id = $response->json('data.id');

        $response = $this->delete("/api/events/$id");
        $response->assertStatus(200);
    }
}
