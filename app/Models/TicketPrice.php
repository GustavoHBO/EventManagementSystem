<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'sector_id',
        'lot_id',
        'price',
    ];

    /**
     * Obtém o setor que pertence ao preço do ingresso.
     * @return BelongsTo
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    /**
     * Obtém o lote que pertence ao preço do ingresso.
     * @return BelongsTo
     */
    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }
}
