<?php

namespace App\Jobs;

use App\Http\Business\OrderBusiness;
use App\Models\Payment;
use Illuminate\Validation\ValidationException;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Throwable;

class PaggueProcessWebhookJob extends ProcessWebhookJob
{
    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function handle(): void
    {
        $payload = $this->webhookCall->payload;
        $payment = Payment::find(($payload['external_id']));
        if ($payload['status'] === 'paid' && (int)number_format($payment->order->total_amount * 100, 0, '',
                '') == $payload['amount']) {
            OrderBusiness::updatePaymentStatus($payment->order, Payment::COMPLETED);
        }
    }
}
