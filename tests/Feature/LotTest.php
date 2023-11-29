<?php

namespace Tests\Feature;

use App\Http\Business\EventBusiness;
use Illuminate\Validation\ValidationException;
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
        $lotId = $response->json('data.lots.0.id');
        $response = $this->get("/api/lots/". $lotId);

        $response->assertStatus(200);
    }

    public function test_create_lot()
    {
        $this->actingAs($this->user);
        $event = EventBusiness::createEvent([
            'name' => 'Event Test',
            'datetime' => now()->addDays(),
            'location' => 'Event Test Location',
            'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD',
            'lots' => [
                [
                    'name' => 'Lot Test',
                    'available_tickets' => 100,
                    'expiration_date' => now()->addDays()->format('Y-m-d'),
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
        $response = $this->login()->post('/api/lots', [
            "event_id" => $event->id, // The ID of the associated event
            "name" => "Early Bird",
            "available_tickets" => 500,
            "expiration_date" => "2023-12-31" // The expiration date for the lot
        ]);

        $response->assertStatus(200);
    }

    /**
     * @throws \Throwable
     * @throws ValidationException
     */
    public function test_update_lot()
    {
        $this->actingAs($this->user);
        $event = EventBusiness::createEvent([
            'name' => 'Event Test',
            'datetime' => now()->addDays(),
            'location' => 'Event Test Location',
            'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD',
            'lots' => [
                [
                    'name' => 'Lot Test',
                    'available_tickets' => 100,
                    'expiration_date' => now()->addDays()->format('Y-m-d'),
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
        $response = $this->login()->put('/api/lots/'.$event->lots()->first()->id, [
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
        $this->actingAs($this->user);
        $event = EventBusiness::createEvent([
            'name' => 'Event Test',
            'datetime' => now()->addDays(),
            'location' => 'Event Test Location',
            'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD',
            'lots' => [
                [
                    'name' => 'Lot Test',
                    'available_tickets' => 100,
                    'expiration_date' => now()->addDays()->format('Y-m-d'),
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
        $response = $this->login()->post('/api/lots', [
            "event_id" => $event->id, // The ID of the associated event
            "name" => "Early Bird",
            "available_tickets" => 500,
            "expiration_date" => "2023-12-31" // The expiration date for the lot
        ]);

        $id = $response->json('data.id');
        $response = $this->delete("/api/lots/$id");

        $response->assertStatus(200);
    }
}
