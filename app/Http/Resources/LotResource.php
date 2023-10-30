<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'available_tickets' => $this->available_tickets,
            'expiration_date' => $this->expiration_date,
            'tickets' => TicketPriceResource::collection($this->ticketPrices)
        ];
    }
}
