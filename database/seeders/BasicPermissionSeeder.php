<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class BasicPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            // Users
            'user create',
            'user list',
            'user edit',
            'user delete',
            'user manage',
            'access admin panel',
            // Teams
            'team create',
            'team list',
            'team delete',
            // Events
            'event manage',
            'event create',
            'event update',
            'event delete',
            'event manage banners',
            'event receive notifications',
            // Orders
            'order list',
            'order create',
            // Payments
            'payment list',
            // Sectors
            'sector create',
            'sector list',
            'sector edit',
            'sector delete',
            // Lots
            'lot create',
            'lot list',
            'lot edit',
            'lot delete',
            // Tickets
            'ticket prices create',
            'ticket create',
            'ticket list',
            'ticket delete',
            'ticket configure sales',
            // Sales
            'sale receive notifications',
            // Permissions
            'permission manage',
            'permission list',
            'permission create',
            'permission edit',
            'permission delete',
            // Roles
            'role list',
            'role create',
            'role edit',
            'role delete'
        ];

        $team = Team::firstOrCreate(['name' => 'default', 'user_id' => 1]);
        setPermissionsTeamId($team->id);

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
        // create roles and assign existing permissions
        $roleSuperAdm = Role::findOrCreate('super admin');

        // Give the role the permissions.
        foreach ($permissions as $permission) {
            $roleSuperAdm->givePermissionTo($permission);
        }

        // Create roles and permissions to producers.
        $roleProducer = Role::findOrCreate('producer');

        $permissionsProducer = [
            'event manage',
            'event create',
            'event update',
            'event delete',
            'event manage banners',
            'event receive notifications',
            'ticket create',
            'ticket list',
            'ticket delete',
            'ticket configure sales',
            'sale receive notifications',
        ];
        foreach ($permissionsProducer as $permission) {
            $roleProducer->givePermissionTo($permission);
        }

        // Create roles and permissions to clients.
        $roleClient = Role::findOrCreate('client');

        $permissionsClient = [
            'event receive notifications',
            'ticket create',
            'ticket list',
            'ticket delete',
            'sale receive notifications',
        ];
        foreach ($permissionsClient as $permission) {
            $roleClient->givePermissionTo($permission);
        }

        // Give the user the role.
        $user = User::find(1);
        $user->assignRole($roleSuperAdm);
    }
}
