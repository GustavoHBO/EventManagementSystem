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
     * Get the coupons for the Event.
     * @return HasMany - Coupons data.
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }
}
