<?php

namespace App\Models;

use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Str;

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
    const CANCELED = 5; // Payment has been canceled.

    protected $fillable = [
        'team_id',
        'order_id',
        'payment_method_id',
        'status_id',
        'amount',
        'payment_date',
    ];
    private PaymentFactory $factoryPayment;

    public function __construct()
    {
        $this->factoryPayment = new PaymentFactory;
        parent::__construct();
    }

    /**
     * Find payment by UUID.
     * @param  string  $uuid  - Payment UUID.
     * @return Payment - Payment data.
     */
    public static function findByUUID(string $uuid): Payment
    {
        return self::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Get the order that owns the Payment.
     * @return HasOne
     */
    public function order(): hasOne
    {
        return $this->hasOne(Order::class);
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

    public function checkout()
    {
        // Generate UUID if not exists.
        if (!$this->uuid) {
            $this->uuid = Str::uuid();
        }
        $payment = $this->factoryPayment->createPayment($this);
        return $payment->pay();
    }
}
