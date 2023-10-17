<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @mixin IdeHelperLot
 */
class Lot extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'available_tickets',
        'expiration_date',
    ];

    /**
     * Get the event that owns the Lot.
     * @return BelongsTo - Event data.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the lot of sectors for the Lot.
     * @return HasMany - LotSectors data.
     */
    public function lotSectors(): HasMany
    {
        return $this->hasMany(LotSector::class);
    }

    /**
     * Get the sectors for the Lot.
     * @return BelongsToMany - Sectors data.
     */
    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class, 'lot_sector', 'lot_id', 'sector_id')->with('ticketPrices');
    }

    /**
     * Get the tickets for the Lot.
     * @return HasManyThrough - Tickets data.
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, TicketPrice::class, 'lot_id', 'ticket_price_id');
    }

    /**
     * Get the ticket prices for the Lot.
     * @return HasMany - TicketPrices data.
     */
    public function ticketPrices(): HasMany
    {
        return $this->hasMany(TicketPrice::class);
    }

    /**
     * Get the quantity of tickets available for this lot using the tickets sold.
     * @return int - Quantity of tickets available for this lot.
     */
    public function availableTickets(): int
    {
        return $this->available_tickets - $this->tickets->whereIn('status_id',
                [TicketStatus::SOLD_OUT, TicketStatus::PENDING_APPROVAL])->count();
    }
}
