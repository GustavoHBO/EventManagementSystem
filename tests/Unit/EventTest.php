<?php

namespace Tests\Unit;

use App\Http\Business\EventBusiness;
use Auth;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Throwable;

class EventTest extends TestCase
{
    /**
     * Get the available lots to sale for the Event.
     */
    public function test_get_available_lots_to_sale()
    {
        try {
            $this->actingAs($this->user);
            $event = EventBusiness::createEvent([
                'name' => 'Event Test',
                'datetime' => now()->addDays(),
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
            $this->assertEquals(1, $event->availableLotsToSale()->get()->count());
        } catch (ValidationException|Throwable $e) {
            $this->fail();
        }
    }

    /**
     * Get the user that owns the Event.
     */
    public function test_get_user()
    {
        try {
            $this->actingAs($this->user);
            $event = EventBusiness::createEvent([
                'name' => 'Event Test',
                'datetime' => now()->addDays(),
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
            $this->assertEquals(Auth::user()->id, $event->user()->get()->first()->id);
        } catch (ValidationException|Throwable $e) {
            dd($e);
            $this->fail();
        }
    }

    /**
     * Get the team that owns the Event.
     */
    public function test_get_team()
    {
        try {
            $this->actingAs($this->user);

            $event = EventBusiness::createEvent([
                'name' => 'Event Test',
                'datetime' => now()->addDays(),
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
            $this->assertEquals(getPermissionsTeamId(), $event->team_id);
        } catch (ValidationException|Throwable $e) {
            $this->fail();
        }
    }

    /**
     * Get the coupons for the Event.
     */
    public function test_get_coupons()
    {
        try {
            $this->actingAs($this->user);
            $event = EventBusiness::createEvent([
                'name' => 'Event Test',
                'datetime' => now()->addDays(),
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
            $this->assertEquals(0, $event->coupons()->get()->count());
        } catch (ValidationException|Throwable $e) {
            $this->fail();
        }
    }
}
