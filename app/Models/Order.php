<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @mixin IdeHelperOrder
 */
class Order extends Model
{
    use HasFactory;

    /**
     * @var array|mixed
     */
    public mixed $paymentData; // Payment data generated by method pay()
    protected $fillable = [
        'user_id',
        'team_id',
        'payment_id',
        'total_amount',
        'status',
    ];

    /**
     * Get the user for the order.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the payment for the order.
     * @return BelongsTo - Payment data.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    /**
     * Get the order items for the order.
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Get the coupon usages for the order.
     * @return HasMany
     */
    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class, 'order_id');
    }

    /**
     * Get the tickets for the order.
     * @return HasManyThrough - The tickets for the order.
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, OrderItem::class, 'order_id', 'order_item_id');
    }

    /**
     * Get the status for the order.
     * @return PaymentStatus - The status for the order.
     */
    public function status(): PaymentStatus
    {
        return $this->belongsTo(PaymentStatus::class, 'status')->get()->first();
    }
}