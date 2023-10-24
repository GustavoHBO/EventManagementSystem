<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class)->orderBy('expiration_date');
    }

    /**
     * Get the available lots to sale for the Event.
     * @return HasOne - Lots data.
     */
    public function availableLotsToSale(): HasOne
    {
        return $this->hasOne(Lot::class)
            ->with('tickets')
            ->where('expiration_date', '>=', now())
            ->orderBy('expiration_date');
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

    /**
     * Get the tickets for the Event.
     * @return HasMany - Tickets data.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the orders for the Event.
     * @return HasMany - Orders data.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
