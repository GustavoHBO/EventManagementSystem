<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperOrderItem
 */
class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'subtotal',
    ];

    /**
     * Get the order that owns the OrderItem.
     * @return BelongsTo - Order that owns the OrderItem.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the tickets for the OrderItem.
     * @return HasMany - Tickets for the OrderItem.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
