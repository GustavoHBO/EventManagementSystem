<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTicket
 */
class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status_id',
        'ticket_price_id',
        'order_item_id'
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

    /**
     * Return true if the ticket is available to be sold. Verify if the ticket is not sold and the event is not
     * expired and if the sector capacity is not exceeded.
     * @return bool - True if the ticket is available to be sold.
     */
    public function isAvailableToBeSold(): bool
    {
        return $this->status_id === TicketStatus::AVAILABLE && $this->ticketPrice->lot->event->datetime > now() && $this->ticketPrice->sector->capacity > $this->ticketPrice->sector->tickets->where('status_id',
                TicketStatus::SOLD_OUT)->count();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}
