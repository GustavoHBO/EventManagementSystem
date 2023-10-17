<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @mixin IdeHelperSector
 */
class Sector extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'price',
    ];

    /**
     * Get the lots that belong to the sector.
     * @return BelongsToMany - Lots data.
     */
    public function lots(): BelongsToMany
    {
        return $this->belongsToMany(Lot::class, 'lot_sector');
    }

    /**
     * Get the events that belong to the sector.
     * @return BelongsToMany - Events data.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_sector');
    }

    /**
     * Get the ticket prices that belong to the sector.
     * @return HasMany - Tickets data.
     */
    public function ticketPrices(): HasMany
    {
        return $this->hasMany(TicketPrice::class);
    }

    /**
     * Get the tickets that belong to the sector using the ticket price.
     * @return HasManyThrough - Tickets data.
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, TicketPrice::class);
    }

}
