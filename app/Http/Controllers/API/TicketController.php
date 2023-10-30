<?php

namespace App\Http\Controllers\API;

use App\Http\Business\TicketBusiness;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    /**
     * Display a listing of the tickets.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tickets = TicketBusiness::getAllTickets();
        return $this->sendSuccessResponse($tickets, 'Tickets recuperados com sucesso!');
    }

    /**
     * Display the specified ticket.
     * @param $id  - Ticket ID.
     * @return JsonResponse - Ticket data.
     */
    public function show($id): JsonResponse
    {
        $ticket = TicketBusiness::getTicketById($id);
        return $this->sendSuccessResponse($ticket, 'Ticket recuperado com sucesso!');
    }

    /**
     * Update the specified ticket.
     * @param  Request  $request  - Request data.
     * @param $id  - Ticket ID.
     * @return JsonResponse - Ticket data.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $ticket = TicketBusiness::updateTicket($id, $request->all());
        return $this->sendSuccessResponse($ticket, 'Ticket atualizado com sucesso!');
    }

    /**
     * Delete the specified ticket.
     * @param $id  - Ticket ID.
     * @return JsonResponse - Ticket data.
     */
    public function destroy($id): JsonResponse
    {
        $ticket = TicketBusiness::deleteTicket($id);
        return $this->sendSuccessResponse($ticket, 'Ticket deletado com sucesso!');
    }
}
