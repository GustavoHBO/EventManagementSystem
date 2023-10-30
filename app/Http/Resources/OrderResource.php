<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'payment_id' => $this->payment_id,
            'total_price' => $this->total_amount,
            'tickets_amount' => $this->tickets()->count(),
            'status' => $this->payment()->first()->paymentStatus()->first()->description,
            'payment_data' => $this->paymentData??null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
