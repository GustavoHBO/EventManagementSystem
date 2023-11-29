<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\TicketStatus;
use Tests\TestCase;

class TicketTest extends TestCase
{
    /**
     * Test if the API returns a list of tickets.
     *
     * @return void
     */
    public function testGetAllTickets()
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
        $response = $this->get('/api/tickets');
        $ticketId = $response->json('data.0.id');
        $ticket = Ticket::find($ticketId);
        $this->assertEquals($ticket->user->id, $ticket->user()->get()->first()->id);
        $this->assertEquals($ticket->status->id, $ticket->status()->get()->first()->id);
        $this->assertEquals($ticket->status_id === TicketStatus::AVAILABLE
            && $ticket->ticketPrice->lot->event->datetime > now()
            && $ticket->ticketPrice->sector->capacity > $ticket->ticketPrice->sector->tickets->where('status_id',
                TicketStatus::SOLD_OUT)->count(), $ticket->isAvailableToBeSold());
        $response->assertStatus(200);
    }

    /**
     * Test if the API returns a ticket by ID.
     *
     * @return void
     */
    public function testGetTicketById()
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
        $response = $this->get('/api/tickets');
        $ticketId = $response->json('data.0.id');
        $response = $this->get('/api/tickets/'.$ticketId);

        $response->assertStatus(200);
    }

    /**
     * Test if the API creates a ticket.
     * @return void
     */
    public function test_fail_create_ticket()
    {
        $response = $this->login()->post('/api/tickets', [
            'title' => 'Teste',
            'description' => 'Teste',
            'status' => 'open',
            'user_id' => 1,
        ]);
        $response->assertStatus(405);
    }

    /**
     * Test if the API updates a ticket.
     *
     * @return void
     */
    public function testUpdateTicket()
    {
        $response = $this->put('/api/tickets/1', [
            'title' => 'Teste',
            'description' => 'Teste',
            'status' => 'open',
            'user_id' => 1,
        ]);

        $response->assertStatus(405);
    }

    /**
     * Test if the API deletes a ticket.
     *
     * @return void
     */
    public function testDeleteTicket()
    {
        $response = $this->delete('/api/tickets/1');

        $response->assertStatus(405);
    }
}
