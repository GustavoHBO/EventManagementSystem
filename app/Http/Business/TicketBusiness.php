<?php

namespace App\Http\Business;

use App\Models\Ticket;
use App\Models\TicketPrice;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class TicketBusiness extends BaseBusiness
{
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
     * @return Collection - Tickets found.
     */
    public static function getAllTickets(): Collection
    {
        $user = Auth::user();
        if($user->hasPermissionTo('ticket list')) {
            if ($user->hasRole(['super admin', 'producer'])) {
                return Ticket::get();
            } elseif ($user->hasRole('client')) {
                return Ticket::where('user_id', $user->id)->get();
            }
        }
        throw new UnauthorizedException(403, 'Você não tem permissão para listar tickets.');
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
        $validParams = Validator::validate($data, TicketBusiness::rulesCreateTicketPrice,
            TicketBusiness::messagesCreateTicketPrice);
        return TicketPrice::create($validParams);
    }
}
