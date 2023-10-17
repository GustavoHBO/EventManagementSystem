<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPayment
 */
class Payment extends Model
{
    use HasFactory;

    // Payment status constants
    const PENDING = 1; // Payment is pending.
    const COMPLETED = 2; // Payment is completed.
    const FAILED = 3; // Payment has failed.
    const REFUNDED = 4; // Payment has been refunded.

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'status_id',
        'amount',
        'payment_date',
    ];

    /**
     * Get the order that owns the Payment
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the payment method that owns the Payment
     * @return BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    /**
     * Get the status that owns the Payment
     * @return BelongsTo
     */
    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'status_id');
    }
}
