<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperLog
 */
class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_type',
        'description',
    ];

    /**
     * Get the user that owns the Log.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
