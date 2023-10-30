<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    /**
     * Test the webhook endpoint.
     */
    public function test_webhook_endpoint()
    {
        $response = $this->login()->post('/api/events', [
            'name' => 'Event Test',
            'datetime' => '2027-12-31 23:59:59',
            'location' => 'Event Test Location',
            'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD',
            'lots' => [
                [
                    'name' => 'Lot Test',
                    'available_tickets' => 100,
                    'expiration_date' => '2027-12-31',
                    'ticket_prices' => [
                        [
                            'sector' => [
                                'name' => 'Sector Test',
                                'capacity' => 100
                            ],
                            'price' => '10.00'
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertStatus(201);
        $ticketPriceId = $response->json('data.lots.0.tickets.0.id');

        $response = $this->post('/api/orders', [
            'payment_method_id' => 1,
            'order_items' => [
                [
                    'ticket_price_id' => $ticketPriceId,
                    'quantity' => 1,
                ],
            ],
        ]);
        $response->assertStatus(201);
        $order = Order::findOrFail($response->json('data.id'));

        $responseData = json_decode($order->payment->response_data, true);
        $hash = $responseData['hash'];

        $this->withHeaders([
            'Signature' => '009f91ff947aca6c2fa3914f95b5fef92f788f096ca206c95b155b6ec0715d52'
        ]);

        $response = $this->post('/api/webhook-receiving-url', [
                "hash" => "$hash",
                "external_id" => $order->payment->uuid,
                "amount" => 1000,
                "status" => "paid",
                "paid_at" => now()->addDay()->format('Y-m-d H:i:s'),

            ]);
        $response->assertStatus(200);
        $order->refresh();
        $this->assertEquals($order->payment->status_id, Payment::COMPLETED);
    }
}
