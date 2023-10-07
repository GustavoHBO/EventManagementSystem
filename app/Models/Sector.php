<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperSector
 */
class Sector extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'price',
    ];

    /**
     * Obtém os lotes que pertencem ao setor.
     * @return BelongsToMany
     */
    public function lots(): BelongsToMany
    {
        return $this->belongsToMany(Lot::class, 'lot_sector');
    }

    /**
     * Obtém os eventos que pertencem ao setor.
     * @return BelongsToMany
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_sector');
    }
}
