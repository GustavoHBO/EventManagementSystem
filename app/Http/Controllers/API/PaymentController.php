<?php

namespace App\Http\Controllers\API;

use App\Http\Business\PaymentBusiness;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     * @return JsonResponse - Payments data.
     */
    public function index(): JsonResponse
    {
        $payments = PaymentBusiness::getAllPayments();
        return $this->sendSuccessResponse($payments, 'Payments recuperados com sucesso!');
    }

    /**
     * Display the specified payment.
     * @param $id  - Payment ID.
     * @return JsonResponse - Payment data.
     */
    public function show($id): JsonResponse
    {
        $payment = PaymentBusiness::getPaymentById($id);
        return $this->sendSuccessResponse($payment, 'Payment recuperado com sucesso!');
    }

    /**
     * Store a newly created payment.
     * @throws ValidationException - If the data is invalid.
     */
    public function store(Request $request): JsonResponse
    {
        $payment = PaymentBusiness::createPayment($request->all());
        return $this->sendSuccessResponse($payment, 'Payment criado com sucesso!');
    }

    /**
     * Update the specified payment.
     * @param  Request  $request  - Request data.
     * @param $id  - Payment ID.
     * @return JsonResponse - Payment data.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $payment = PaymentBusiness::updatePayment($id, $request->all());
        return $this->sendSuccessResponse($payment, 'Payment atualizado com sucesso!');
    }

    /**
     * Delete the specified payment.
     * @param $id  - Payment ID.
     * @return JsonResponse - Payment data.
     */
    public function destroy($id): JsonResponse
    {
        $payment = PaymentBusiness::deletePayment($id);
        return $this->sendSuccessResponse($payment, 'Payment deletado com sucesso!');
    }
}
