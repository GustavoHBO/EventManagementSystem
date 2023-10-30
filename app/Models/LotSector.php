<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperLotSector
 */
class LotSector extends Model
{
    use HasFactory;

    protected $table = 'lot_sector';

    protected $fillable = [
        'lot_id',
        'sector_id',
    ];

    /**
     * Get the lot that owns the LotSector.
     * @return BelongsTo - Lot data.
     */
    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    /**
     * Get the sector that owns the LotSector.
     * @return BelongsTo - Sector data.
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }
}
