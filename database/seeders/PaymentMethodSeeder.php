<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            ['name' => 'PIX', 'description' => 'Realize o pagamento com pix web da Paggue'],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::firstOrCreate($method);
        }
    }
}
