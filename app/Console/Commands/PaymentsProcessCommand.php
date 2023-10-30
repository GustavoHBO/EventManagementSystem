<?php

namespace App\Console\Commands;

use App\Http\Business\OrderBusiness;
use App\Models\Log;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class PaymentsProcessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:payments-process-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process payments that are pending for more than 10 minutes. Cancel the pending payments and return the tickets to the inventory.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $payments = Payment::where('status_id', Payment::PENDING)->where('created_at', '<=',
            now()->subMinutes(10))->get();
        foreach ($payments as $payment) {
            try {
                OrderBusiness::cancelOrder($payment->order);
                Log::create([
                    'event_type' => 'PAYMENT_EXPIRED_TIME',
                    'description' => 'Payment canceled due to expired time.',
                ]);
            } catch (ValidationException|\Throwable $e) {
                Log::create([
                    'event_type' => 'PAYMENT_EXPIRED_TIME',
                    'description' => 'Error processing payment: ' . $e->getMessage(),
                ]);
            }
        }
    }
}
