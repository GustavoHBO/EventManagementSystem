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
        $response = $this->login()->get('/api/tickets');
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
        $response = $this->login()->get('/api/tickets/1');

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
