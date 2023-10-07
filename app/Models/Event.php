<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperEvent
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'datetime',
        'location',
        'banner',
    ];

    /**
     * Obtém o usuário que criou o evento.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
