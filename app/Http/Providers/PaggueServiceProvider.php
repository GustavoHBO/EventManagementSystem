<?php

namespace App\Http\Providers;

use App\Models\Payment;
use Http;
use Illuminate\Http\Client\Response;

class PaggueServiceProvider
{

    /**
     * Get the data to bill a order.
     * @param  Payment  $payment  - Payment data.
     * @return array - Response data.
     */
    public static function billinOrder(Payment $payment): array
    {
        $authData = self::login()->json();
        $payment->response_data = Http::withHeaders([
            'X-Company-ID' => $authData['user']['companies'][0]['id'],
            'Signature' => '',
            'Content-Type' => 'application/json',
            'Authorization' => $authData['token_type'].' '.$authData['access_token'],
        ])->post(env('PAGGUE_URL').'/billing_order', [
            'payer_name' => \Auth::user()->name,
            'amount' => $payment->amount * 100,
            'external_id' => $payment->uuid,
            'description' => $payment->payment_method_id,
            'meta' => $payment->toArray(),
        ]);
        $payment->save();
        return $payment->response_data->json();
    }

    /**
     * Login to Paggue API.
     * @return Response - Response data.
     */
    public static function login(): Response
    {
        return Http::post(env('PAGGUE_URL').'/auth/login', [
            'client_key' => env('PAGGUE_CLIENT_KEY'),
            'client_secret' => env('PAGGUE_CLIENT_SECRET')
        ]);
    }
}
