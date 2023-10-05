<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot_id',
        'sector_id',
        // Adicione outras colunas conforme necessário
    ];

    // Defina as relações com as tabelas 'lots' e 'sectors'
    public function lot(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function sector(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }
}
