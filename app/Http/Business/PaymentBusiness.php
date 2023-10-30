<?php

namespace App\Http\Business;

use App\Models\Payment;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PaymentBusiness extends BaseBusiness
{

    const rules = [
        'order_id' => 'required|integer|exists:orders,id',
        'payment_method_id' => 'required|integer|exists:payment_methods,id',
        'status_id' => 'required|integer|exists:payment_statuses,id',
        'amount' => 'required|numeric|min:0',
        'payment_date' => 'date|nullable',
    ];

    const messages = [
        'order_id.required' => 'The order ID is required.',
        'order_id.integer' => 'The order ID must be an integer.',
        'order_id.exists' => 'The selected order does not exist in the database.',

        'payment_method_id.required' => 'The payment method ID is required.',
        'payment_method_id.integer' => 'The payment method ID must be an integer.',
        'payment_method_id.exists' => 'The selected payment method does not exist in the database.',

        'status_id.required' => 'The status ID is required.',
        'status_id.integer' => 'The status ID must be an integer.',
        'status_id.exists' => 'The selected status does not exist in the database.',

        'amount.required' => 'The amount is required.',
        'amount.numeric' => 'The amount must be a number.',
        'amount.min' => 'The amount must be at least 0.',

        'payment_date.date' => 'The payment date must be a date.',
    ];

    /**
     * Get a payment by ID.
     * @param $id  - Payment ID.
     * @return Payment - Payment found.
     * @throws UnauthorizedException - If the user does not have permission to view payments.
     */
    public static function getPaymentById($id): Payment
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('payment list')) {
            if ($user->hasRole(['super admin', 'producer'])) {
                return Payment::where('team_id', getPermissionsTeamId())->where('id', $id)->first();
            } elseif ($user->hasRole('client')) {
                return Payment::where('team_id', getPermissionsTeamId())->where('user_id', $user->id)->where('id',
                    $id)->first();
            }
        }
        throw new UnauthorizedException(403, 'Você não tem permissão para listar pedidos.');
    }

    /**
     * Get all payments.
     * @return Collection - Payments found.
     */
    public static function getAllPayments(): Collection
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('payment list')) {
            if ($user->hasRole(['super admin', 'producer'])) {
                return Payment::where('team_id', getPermissionsTeamId())->get();
            } elseif ($user->hasRole('client')) {
                return Payment::where('team_id', getPermissionsTeamId())->where('user_id', $user->id)->get();
            }
        }
        throw new UnauthorizedException(403, 'Você não tem permissão para listar pedidos.');
    }
}
