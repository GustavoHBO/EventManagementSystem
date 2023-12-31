<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPaymentStatus
 */
class PaymentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];
}
