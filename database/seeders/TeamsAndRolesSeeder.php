<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TeamsAndRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar times (equipes)
        $salesTeam = Team::create(['name' => 'Sales']);
        $marketingTeam = Team::create(['name' => 'Marketing']);

        // Criar regras (papéis)
        $salesRole = Role::create(['name' => 'sales']);
        $marketingRole = Role::create(['name' => 'marketing']);

        // Atribuir regras (papéis) às equipes
        $salesTeam->assignRole($salesRole);
        $marketingTeam->assignRole($marketingRole);

    }
}
