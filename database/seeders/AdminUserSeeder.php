<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt(env('ADMIN_DEFAULT_PASSWORD') ?? 'password'), // Replace with a secure password
            'phone' => '12345678901',
            'cpf_cnpj' => '12345678901',
        ]);
    }
}
