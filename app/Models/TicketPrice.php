<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperTicketPrice
 */
class TicketPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'sector_id',
        'lot_id',
        'price',
    ];

    /**
     * Get the sector that owns the TicketPrice.
     * @return BelongsTo
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    /**
     * Get the lot that owns the TicketPrice.
     * @return BelongsTo
     */
    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    /**
     * Get the tickets that belong to the ticket price.
     * @return HasMany - Tickets data.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the quantity of tickets available for this ticket price.
     * @return int - Quantity of tickets available for this ticket price.
     */
    public function availableTickets(): int
    {
        $qty = $this->sector->capacity - $this->tickets->whereIn('status_id',
                [TicketStatus::SOLD_OUT, TicketStatus::PENDING_APPROVAL])->count();
        return min($this->lot->availableTickets(), max($qty, 0));
    }
}
