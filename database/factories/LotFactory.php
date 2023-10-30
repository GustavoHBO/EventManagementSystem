<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lot>
 */
class LotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return  [
            'event_id' => function () {
                // You can use a factory or a specific event ID here.
                // Example: return factory(App\Models\Event::class)->create()->id;
                // Alternatively, you can use a specific event ID if needed.
                return 1; // Change this to the desired event ID.
            },
            'name' => $faker->word,
            'available_tickets' => $faker->numberBetween(50, 200), // Adjust the available_tickets range as needed.
        ];
    }
}
