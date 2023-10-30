<?php

namespace Tests\Feature;

use App\Models\Coupon;
use Illuminate\Support\Str;
use Tests\TestCase;

class CouponControllerTest extends TestCase
{
    /**
     * Test if the user can get all coupons.
     *
     * @return void
     */
    public function testGetAllCoupons()
    {
        $response = $this->login()->get('/api/coupons');
        $response->assertStatus(200);
    }

    /**
     * Test if the user can get a coupon by id.
     *
     * @return void
     */
    public function testGetCouponById()
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
        $id = $response->json('data.id');

        $response = $this->post('/api/coupons', [
            'name' => 'Coupon Test',
            'code' => Str::random(7),
            'discount_percentage' => 10,
            'expiration_date' => '2021-12-31 23:59:59',
            'event_id' => $id,
        ]);

        $response->assertStatus(201);
        $id = $response->json('data.id');

        $response = $this->get("/api/coupons/$id");

        $response->assertStatus(200);
    }

    /**
     * Test if the user can create a coupon.
     *
     * @return void
     */
    public function testCreateCoupon()
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
        $id = $response->json('data.id');
        $code = Str::random(7);
        $response = $this->post('/api/coupons', [
            'name' => 'Coupon Test',
            'code' => $code,
            'discount_percentage' => 10,
            'expiration_date' => '2021-12-31 23:59:59',
            'event_id' => $id,
        ]);

        $response->assertStatus(201);

        $couponId = $response->json('data.id');
        $coupon = Coupon::find($couponId);

        $this->assertNotNull($coupon);
        $this->assertEquals($coupon->code, $code);

        $this->assertEquals($this->user->id, $coupon->user()->get()->first()->id);
    }

    /**
     * Test if the user can update a coupon.
     *
     * @return void
     */
    public function testUpdateCoupon()
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
        $id = $response->json('data.id');

        $response = $this->post('/api/coupons', [
            'name' => 'Coupon Test',
            'code' => Str::random(7),
            'discount_percentage' => 10,
            'expiration_date' => '2021-12-31 23:59:59',
            'event_id' => $id,
        ]);

        $response->assertStatus(201);
        $id = $response->json('data.id');

        $response = $this->put("/api/coupons/$id", [
            'expires_at' => '2021-12-31 23:59:59',
        ]);

        $response->assertStatus(200);
    }
}
