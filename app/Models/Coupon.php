<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCoupon
 */
class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'code',
        'discount_percentage',
        'max_usages',
        'expiration_date',
        'user_id'
    ];


    /**
     * Get the event that owns the coupon.
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Get the user that created the coupon.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
