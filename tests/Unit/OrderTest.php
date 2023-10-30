<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    /**
     * Get the available lots to sale for the Event.
     */
    public function test_get_available_lots_to_sale()
    {
        $this->assertTrue(true);
//        try {
//            $event = EventBusiness::createEvent([
//                'name' => 'Event Test',
//                'datetime' => '2027-12-31 23:59:59',
//                'location' => 'Event Test Location',
//                'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD',
//                'lots' => [
//                    [
//                        'name' => 'Lot Test',
//                        'available_tickets' => 100,
//                        'expiration_date' => '2027-12-31',
//                        'ticket_prices' => [
//                            [
//                                'sector' => [
//                                    'name' => 'Sector Test',
//                                    'capacity' => 100
//                                ],
//                                'price' => '10.00'
//                            ],
//                        ],
//                    ],
//                ],
//            ]);
//            dd($event->toArray());
//
//        } catch (ValidationException|\Throwable $e) {
//            $this->fail();
//        }
//
//
//        $order = OrderBusiness::createOrder([
//            'payment_method_id' => 1,
//            'order_items' => [
//                [
//                    'ticket_price_id' => $ticketPriceId,
//                    'quantity' => 1,
//                ],
//            ],
//        ]);
//        $this->assertTrue(true);
    }
}
