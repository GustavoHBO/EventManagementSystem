<?php

namespace App\Http\Business;

use App\Models\Payment;
use Auth;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Validator;

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
     * Create a new Payment instance and return it.
     * @throws ValidationException - If the data is invalid.
     * @throws UnauthorizedException - If the user does not have permission to create a payment.
     * @throws ValidationException - If the data is invalid.
     */
    public static function createPayment($data): Payment
    {
        BaseBusiness::hasPermissionTo('create payments');
        $validParams = Validator::validate($data, PaymentBusiness::rules, PaymentBusiness::messages);
        return Payment::create($validParams);
    }

    /**
     * Update a payment and return it.
     * @param $id  - Payment ID.
     * @param $data  - Payment data.
     * @return Payment - Payment updated.
     * @throws UnauthorizedException - If the user does not have permission to update a payment.
     */
    public static function updatePayment($id, $data): Payment
    {
        BaseBusiness::hasPermissionTo('update payments');
        $payment = Payment::find($id);
        $payment->update($data);
        return $payment;
    }

    /**
     * Delete a payment and return it.
     * @param  int  $id  - Payment ID.
     * @return Payment - Payment deleted.
     * @throws UnauthorizedException - If the user does not have permission to delete a payment.
     */
    public static function deletePayment(int $id): Payment
    {
        BaseBusiness::hasPermissionTo('delete payments');
        $payment = Payment::find($id);
        $payment->delete();
        return $payment;
    }

    /**
     * Get a payment by ID.
     * @param $id  - Payment ID.
     * @return Payment - Payment found.
     * @throws UnauthorizedException - If the user does not have permission to view payments.
     */
    public static function getPaymentById($id): Payment
    {
        BaseBusiness::hasPermissionTo('view payments');
        return Payment::find($id);
    }

    /**
     * Get all payments.
     * @return array - Payments found.
     * @throws UnauthorizedException - If the user does not have permission to view payments.
     */
    public static function getAllPayments(): array
    {
        BaseBusiness::hasPermissionTo('view payments');
        return Payment::all()->toArray();
    }

    /**
     * Get all my payments.
     * @return array - Payments found.
     */
    public static function getAllMyPayments(): array
    {
        return Payment::where('user_id', Auth::user()->id)->get()->toArray();
    }
}
