<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\PaymentMethod;
use Brick\Math\BigInteger;
use Brick\Math\Exception\MathException;
use Str;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    /**
     * Get all payments.
     */
    public function test_get_all_payments()
    {
        $response = $this->login()->get('/api/payments');

        $response->assertStatus(200);
    }

    /**
     * Get a payment by id.
     * @throws MathException
     */
    public function test_get_payment_by_id()
    {
        $method = PaymentMethod::first();
        $payment = Payment::firstOrCreate([
            'uuid' => (string)Str::uuid(), // Unique identifier of the payment.
            'team_id' => getPermissionsTeamId(),
            'payment_method_id' => $method->id,
            'status_id' => Payment::PENDING, // Status of the payment (1 = Pending, 2 = Paid, 3 = Canceled).
            'amount' => BigInteger::randomRange(0, 100), // The amount of the payment.
        ]);
        $response = $this->login()->get("/api/payments/". $payment->id);
        $response->assertStatus(200);
    }

    public function test_get_payment_by_id_not_found()
    {
        $this->login()->get('/api/payments'.fake()->numberBetween(100, 200))->assertStatus(404);
    }

    /**
     * Create a payment.
     */
    public function test_create_payment()
    {
        $response = $this->login()->post('/api/payments', [
            'order_id' => 1,
            'payment_method_id' => 1,
            'status_id' => 1,
            'amount' => 1,
            'payment_date' => '2021-01-01',
        ]);

        $response->assertStatus(405);
    }

    /**
     * Update a payment.
     */
    public function test_update_payment()
    {
        $response = $this->login()->put('/api/payments/1', [
            'order_id' => 1,
            'payment_method_id' => 1,
            'status_id' => 1,
            'amount' => 1,
            'payment_date' => '2021-01-01',
        ]);

        $response->assertStatus(405);
    }

    /**
     * Delete a payment.
     */
    public function test_delete_payment()
    {
        $response = $this->login()->delete('/api/payments/1');

        $response->assertStatus(405);
    }
}
