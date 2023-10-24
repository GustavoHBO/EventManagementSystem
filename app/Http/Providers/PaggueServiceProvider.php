<?php

namespace App\Http\Providers;

use App\Models\Payment;
use GuzzleHttp\Promise\PromiseInterface;
use Http;
use Illuminate\Http\Client\Response;

class PaggueServiceProvider
{

    /**
     * Get the data to bill a order.
     * @param  Payment  $payment  - Payment data.
     * @return PromiseInterface|Response - Response data.
     */
    public static function billinOrder(Payment $payment): PromiseInterface|Response
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
        return $payment->response_data;
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

    public static function getPaymentData(Payment $payment)
    {
        $authData = self::login()->json();
        $page = 1;
        do {
            $initialDate = $payment->created_at?->format('Y-m-d') ?? now()->format('Y-m-d');
            $finalDate = $payment->created_at?->addDay()->format('Y-m-d') ?? now()->addDay()->format('Y-m-d');
            $response = Http::withHeaders([
                'X-Company-ID' => $authData['user']['companies'][0]['id'],
                'Signature' => '',
                'Content-Type' => 'application/json',
                'Authorization' => $authData['token_type'].' '.$authData['access_token'],
            ])->get(env('PAGGUE_URL').'/billing_order/', [
                    'order' => 'created_at,desc',
                    'between[]' => "created_at,{$initialDate},{$finalDate}",
                    'page' => $page++
                ]);
            foreach ($response->json()['data'] as $data) {
                if ($data['external_id'] == $payment->id) {
                    return $data;
                }
            }
        } while ($response->json()['meta']['current_page'] < $response->json()['meta']['last_page']);
        return self::billinOrder($payment)->json();
    }
}
