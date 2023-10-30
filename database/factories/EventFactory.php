<?php

namespace Database\Factories;

use App\Http\Business\EventBusiness;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return EventBusiness::createEvent([
            'user_id' => 1,
            'name' => fake()->sentence,
            'datetime' => fake()->date(),
            'location' => fake()->city,
            'banner' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/', // You may need to adjust this path.
            'lots' => [
                [
                    'name' => 'Lot 1',
                    'available_tickets' => fake()->numberBetween(50, 200),
                    'expiration_date' => fake()->date(),
                    "ticket_prices" => [
                        [
                            "sector" => [
                                "name" => fake()->word,
                                "capacity" => fake()->numberBetween(50, 200),
                            ],
                            "price" => fake()->numberBetween(50, 200)
                        ]
                    ],
                ]
            ],
        ])->toArray();
    }
}
