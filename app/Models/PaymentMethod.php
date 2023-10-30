<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPaymentMethod
 */
class PaymentMethod extends Model
{
    use HasFactory;

    const PIX = 1; // Pix payment method.
    protected $fillable = [
        'name',
        'description',
    ];
}
