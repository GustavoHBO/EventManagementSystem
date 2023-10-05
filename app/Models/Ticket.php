<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status_id',
        'ticket_price_id',
    ];

    /**
     * Get the user that owns the Ticket.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the status that owns the Ticket.
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'status_id');
    }

    /**
     * Get the ticketPrice that owns the Ticket.
     * @return BelongsTo
     */
    public function ticketPrice(): BelongsTo
    {
        return $this->belongsTo(TicketPrice::class, 'ticket_price_id');
    }
}
