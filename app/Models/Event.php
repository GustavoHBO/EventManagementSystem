<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperEvent
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id',
        'name',
        'datetime',
        'location',
        'banner',
    ];

    /**
     * Get the user that owns the Event.
     * @return BelongsTo - User data.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lots for the Event.
     * @return HasMany - Lots data.
     */
    public function lots()
    {
        return $this->hasMany(Lot::class);
    }

    /**
     * Get the team that owns the Event.
     * @return BelongsTo - Team data.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the tickets for the Event.
     * @return HasMany - Tickets data.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
* Get the ticket prices for the Event.
     * @return HasMany - TicketPrices data.
     */
    public function ticketPrices(): HasMany
    {
        return $this->hasMany(TicketPrice::class);
    }

    /**
     * Get the sectors for the Event.
     * @return HasMany - Sectors data.
     */
    public function sectors(): HasMany
    {
        return $this->tickets()->sectors();
    }
}
