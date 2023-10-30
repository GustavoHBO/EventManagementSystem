<?php

namespace Tests\Feature;

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
     */
    public function test_get_payment_by_id()
    {
        $response = $this->login()->get('/api/payments/1');

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
