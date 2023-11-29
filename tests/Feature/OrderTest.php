<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * Test if the user can get all orders.
     */
    public function test_get_all_orders(): void
    {
        $response = $this->login()->get('/api/orders');
        $response->assertStatus(200);
    }

    /**
     * Test if the user can get an order by ID.
     */
    public function test_get_order_by_id(): void
    {
        $response = $this->login()->post('/api/events', [
            'name' => 'Event Test',
            'datetime' => '2021-12-31 23:59:59',
            'location' => 'Event Test Location',
            'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD',
            'lots' => [
                [
                    'name' => 'Lot Test',
                    'available_tickets' => 100,
                    'expiration_date' => '2021-12-31',
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
        $id = $response->json()['data']['id'];
        $response = $this->get("/api/orders/$id");
        $response->assertStatus(200);
    }

    /**
     * Test if the user can create an order.
     */
    public function test_create_order(): void
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
        $order = Order::find($response->json('data.id'));

        $this->assertNotNull($order);
        $this->assertCount(1, $order->orderItems()->get());
        $this->assertEquals(1, $order->orderItems()->get()->first->order()->get()->first()->id);

        $orderItemId = $order->orderItems()->get()->first()->id;

        $ticket = Ticket::where('order_item_id', $order->orderItems()->get()->first()->id)->get()->first();
        $this->assertEquals($ticket->status_id, TicketStatus::getStatusIdByPaymentId($order->payment->first()->id));
        $this->assertNotNull($ticket);
        $this->assertEquals($orderItemId, $ticket->orderItem()->get()->first()->id);

        $paymentByUUID = Payment::findByUUID($order->payment()->get()->first()->uuid);
        $this->assertNotNull($paymentByUUID);
        $this->assertEquals($order->payment, $paymentByUUID);

        $this->assertEquals(1, $order->payment->paymentMethod()->get()->first()->id);

        $this->assertEquals($paymentByUUID->status_id, $order->payment->paymentStatus()->get()->first()->id);

        $this->assertEquals($order->payment->id, $paymentByUUID->order()->get()->first()->payment_id);

        $this->assertEquals($order->user, $this->user);
        $this->assertEquals(0, $order->couponUsages()->count());

        $this->assertEquals($paymentByUUID->status_id, $order->status()->first()->id);

        $paymentStatus = PaymentStatus::find($paymentByUUID->status_id);
        $this->assertNotNull($paymentStatus);

        $paymentMethod = $order->payment->paymentMethod()->get()->first();
        $this->assertNotNull($paymentMethod);
        $paymentMethodData = PaymentMethod::find($paymentMethod->id);
        $this->assertNotNull($paymentMethodData);
        $this->assertEquals($paymentMethodData->id, PaymentMethod::PIX);
    }

    /**
     * Test create order with coupon.
     * @return void
     */
    public function test_create_order_with_coupon(): void
    {
        $response = $this->login()->post('/api/events', [
            'name' => 'Event Test',
            'datetime' => now()->addDays()->format('Y-m-d H:i:s'),
            'location' => 'Event Test Location',
            'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD',
            'lots' => [
                [
                    'name' => 'Lot Test',
                    'available_tickets' => 100,
                    'expiration_date' => now()->addDays()->format('Y-m-d'),
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
        $eventId = $response->json('data.id');
        $ticketPriceId = $response->json('data.lots.0.tickets.0.id');

        $code = Str::random(7);
        $response = $this->post('/api/coupons', [
            'name' => 'Coupon Test',
            'code' => $code,
            'discount_percentage' => 10,
            'expiration_date' => now()->addDays(10)->format('Y-m-d'),
            'event_id' => $eventId,
        ]);
        $response->assertStatus(201);

        $response = $this->post('/api/orders', [
            'payment_method_id' => 1,
            'coupon_code' => $code,
            'order_items' => [
                [
                    'ticket_price_id' => $ticketPriceId,
                    'quantity' => 1,
                ],
            ],
        ]);
        $response->assertStatus(201);
        $order = Order::find($response->json('data.id'));

        $this->assertNotNull($order);
        $this->assertCount(1, $order->orderItems()->get());
        $this->assertEquals(1, $order->orderItems()->get()->first->order()->get()->first()->id);

        $orderItemId = $order->orderItems()->get()->first()->id;

        $ticket = Ticket::where('order_item_id', $order->orderItems()->get()->first()->id)->get()->first();
        $this->assertEquals($ticket->status_id, TicketStatus::getStatusIdByPaymentId($order->payment->first()->id));
        $this->assertNotNull($ticket);
        $this->assertEquals($orderItemId, $ticket->orderItem()->get()->first()->id);

        $paymentByUUID = Payment::findByUUID($order->payment()->get()->first()->uuid);
        $this->assertNotNull($paymentByUUID);
        $this->assertEquals($order->payment, $paymentByUUID);

        $couponUsage = $order->couponUsages()->get()->first();
        $this->assertNotNull($couponUsage);
        $this->assertEquals($couponUsage->coupon()->get()->first()->code, $code);
        $this->assertEquals($couponUsage->order()->get()->first()->id, $order->id);
    }

    public function test_delete_order(): void
    {
        $response = $this->login()->post('/api/events', [
            'name' => 'Event Test',
            'datetime' => '2021-12-31 23:59:59',
            'location' => 'Event Test Location',
            'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD',
            'lots' => [
                [
                    'name' => 'Lot Test',
                    'available_tickets' => 100,
                    'expiration_date' => '2021-12-31',
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
        $id = $response->json()['data']['id'];

        $response = $this->delete('/api/orders/'.$id);
        $response->assertStatus(200);
    }
}
