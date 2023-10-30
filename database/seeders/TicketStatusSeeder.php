<?php

namespace Database\Seeders;

use App\Models\TicketStatus;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Available',
                'description' => 'Tickets are available for purchase',
            ],
            [
                'name' => 'Sold Out',
                'description' => 'All tickets have been sold',
            ],
            [
                'name' => 'Reserved',
                'description' => 'Tickets are reserved but not sold',
            ],
            [
                'name' => 'Pending Approval',
                'description' => 'Tickets are pending approval for purchase',
            ],
            [
                'name' => 'Cancelled',
                'description' => 'Cancelled tickets',
            ]
        ];
        foreach ($statuses as $status) {
            TicketStatus::firstOrCreate($status);
        }
    }
}
