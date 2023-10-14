<?php

namespace App\Http\Business;

use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class TicketBusiness extends BaseBusiness
{
    const rules = [
        'user_id' => 'required|integer|exists:users,id',
        'status_id' => 'required|integer|exists:ticket_statuses,id',
        'ticket_price_id' => 'required|integer|exists:ticket_prices,id',
    ];

    const messages = [
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

    /**
     * Create a new Ticket instance and return it.
     * @throws ValidationException - If the data is invalid.
     */
    public static function createTicket($data): Ticket
    {
        BaseBusiness::hasPermissionTo('create tickets');
        $validParams = Validator::validate($data, TicketBusiness::rules, TicketBusiness::messages);
        return Ticket::create($validParams);
    }

    /**
     * Update a ticket and return it.
     * @param $id - Ticket ID.
     * @param $data  - Ticket data.
     * @return Ticket - Ticket updated.
     */
    public static function updateTicket($id, $data): Ticket
    {
        BaseBusiness::hasPermissionTo('update tickets');
        $ticket = Ticket::find($id);
        $ticket->update($data);
        return $ticket;
    }

    /**
     * Delete a ticket and return it.
     * @param  int  $id - Ticket ID.
     * @return Ticket - Ticket deleted.
     */
    public static function deleteTicket(int $id): Ticket
    {
        BaseBusiness::hasPermissionTo('delete tickets');
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
        BaseBusiness::hasPermissionTo('view tickets');
        return Ticket::find($id);
    }

    /**
     * Get all tickets.
     * @return array - Tickets found.
     * @throws UnauthorizedException - If the user does not have permission to view tickets.
     */
    public static function getAllTickets(): array
    {
        BaseBusiness::hasPermissionTo('view tickets');
        return Ticket::all()->toArray();
    }
}
