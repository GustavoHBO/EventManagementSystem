<?php

namespace App\Http\Business;

use App\Models\Ticket;
use App\Models\TicketPrice;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class TicketBusiness extends BaseBusiness
{
    // Rules to create a ticket.
    const rulesCreateTicket = [
        'user_id' => 'required|integer|exists:users,id',
        'status_id' => 'required|integer|exists:ticket_statuses,id',
        'ticket_price_id' => 'required|integer|exists:ticket_prices,id',
    ];

    // Messages to be returned when validation fails on create ticket.
    const messagesCreateTicket = [
        'user_id.required' => 'The user ID is required.',
        'user_id.integer' => 'The user ID must be an integer.',
        'user_id.exists' => 'The selected user does not exist in the database.',

        'status_id.required' => 'The status ID is required.',
        'status_id.integer' => 'The status ID must be an integer.',
        'status_id.exists' => 'The selected status does not exist in the database.',

        'ticket_price_id.required' => 'The ticket price ID is required.',
        'ticket_price_id.integer' => 'The ticket price ID must be an integer.',
        'ticket_price_id.exists' => 'The selected ticket price does not exist in the database.',
    ];

    // Rules to create a ticket price.
    const rulesCreateTicketPrice = [
        'sector_id' => 'required|integer|exists:sectors,id',
        'lot_id' => 'required|integer|exists:lots,id',
        'price' => 'required|numeric|min:0',
    ];

    // Messages to be returned when validation fails on create ticket price.
    const messagesCreateTicketPrice = [
        'sector_id.required' => 'The sector ID is required.',
        'sector_id.integer' => 'The sector ID must be an integer.',
        'sector_id.exists' => 'The selected sector does not exist in the database.',

        'lot_id.required' => 'The lot ID is required.',
        'lot_id.integer' => 'The lot ID must be an integer.',
        'lot_id.exists' => 'The selected lot does not exist in the database.',

        'price.required' => 'The price is required.',
        'price.numeric' => 'The price must be a number.',
        'price.min' => 'The price must be at least 0.',
    ];

    /**
     * Create a new Ticket instance and return it.
     * @throws ValidationException - If the data is invalid.
     */
    public static function createTicket($data): Ticket
    {
        BaseBusiness::hasPermissionTo('ticket create');
        $validParams = Validator::validate($data, TicketBusiness::rulesCreateTicket,
            TicketBusiness::messagesCreateTicket);
        return Ticket::create($validParams);
    }

    /**
     * Update a ticket and return it.
     * @param $id  - Ticket ID.
     * @param $data  - Ticket data.
     * @return Ticket - Ticket updated.
     */
    public static function updateTicket($id, $data): Ticket
    {
        BaseBusiness::hasPermissionTo('ticket edit');
        $ticket = Ticket::find($id);
        $ticket->update($data);
        return $ticket;
    }

    /**
     * Delete a ticket and return it.
     * @param  int  $id  - Ticket ID.
     * @return Ticket - Ticket deleted.
     */
    public static function deleteTicket(int $id): Ticket
    {
        BaseBusiness::hasPermissionTo('ticket delete');
        $ticket = Ticket::find($id);
        $ticket->delete();
        return $ticket;
    }

    /**
     * Get a ticket by ID.
     * @param $id  - Ticket ID.
     * @return Ticket - Ticket found.
     * @throws UnauthorizedException - If the user does not have permission to view tickets.
     */
    public static function getTicketById($id): Ticket
    {
        BaseBusiness::hasPermissionTo('ticket list');
        return Ticket::find($id);
    }

    /**
     * Get all tickets.
     * @return array - Tickets found.
     * @throws UnauthorizedException - If the user does not have permission to view tickets.
     */
    public static function getAllTickets(): array
    {
        BaseBusiness::hasPermissionTo('ticket list');
        return Ticket::all()->toArray();
    }

    /**
     * Create a new TicketPrice instance and return it.
     * @param $data  - TicketPrice data.
     * @throws ValidationException - If the data is invalid.
     * @throws UnauthorizedException - If the user does not have permission to create ticket prices.
     */
    public static function createTicketPrice($data): TicketPrice
    {
        BaseBusiness::hasPermissionTo('ticket prices create');
        $validParams = Validator::validate($data, TicketBusiness::rulesCreateTicketPrice, TicketBusiness::messagesCreateTicketPrice);
        return TicketPrice::create($validParams);
    }
}
