<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissões de acesso geral
        Permission::create(['name' => 'view events']);
        Permission::create(['name' => 'view event details']);
        Permission::create(['name' => 'register']);
        Permission::create(['name' => 'login']);
        Permission::create(['name' => 'logout']);
        Permission::create(['name' => 'view own profile']);

        // Permissões de acesso de usuários autenticados
        Permission::create(['name' => 'create events']);
        Permission::create(['name' => 'update events']);
        Permission::create(['name' => 'delete events']);
        Permission::create(['name' => 'buy tickets']);
        Permission::create(['name' => 'view purchased tickets']);
        Permission::create(['name' => 'view own created events']);
        Permission::create(['name' => 'manage users']);

        // Permissões específicas para administradores
        Permission::create(['name' => 'access admin panel']);
        Permission::create(['name' => 'manage events']);
        Permission::create(['name' => 'manage permissions']);

        // Permissões específicas para produtores de eventos
        Permission::create(['name' => 'manage event banners']);
        Permission::create(['name' => 'configure ticket sales']);
        Permission::create(['name' => 'receive sale notifications']);

        // Permissões específicas para clientes
        Permission::create(['name' => 'receive event notifications']);
    }
}
