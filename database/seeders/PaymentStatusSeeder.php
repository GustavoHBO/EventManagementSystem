<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentStatuses = [
            ['name' => 'Pending', 'description' => 'Pagamento pendente.'],
            ['name' => 'Completed', 'description' => 'Pagamento concluído.'],
            ['name' => 'Failed', 'description' => 'Pagamento falhou.'],
            ['name' => 'Refunded', 'description' => 'Pagamento reembolsado.']
        ];
        foreach ($paymentStatuses as $status) {
            PaymentStatus::firstOrCreate($status);
        }
    }
}
