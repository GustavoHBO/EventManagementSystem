<?php

namespace App\Http\Business;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Validator;

class RolePermissionBusiness extends BaseBusiness
{
    const createRoleRules = [
        'name' => 'required|unique:roles,name,team_id,NULL,id',
        'team_id' => 'required|exists:teams,id'
    ];

    const createPermissionRules = [
        'name' => 'required|unique:permissions,name,team_id,NULL,id',
        'team_id' => 'required|exists:teams,id',
    ];

    /**
     * Create a new Role instance and return it.
     * @throws ValidationException - If the data is invalid.
     * @throws UnauthorizedException - If the user does not have permission to create a role.
     */
    public static function createRole($data): Builder|Model
    {
        BaseBusiness::hasPermissionTo('create roles');
        $data['team_id'] = getPermissionsTeamId();
        $validParams = Validator::validate($data, RolePermissionBusiness::createRoleRules);
        return Role::create($validParams);
    }

    /**
     * Create a new Permission instance and return it.
     * @throws ValidationException - If the data is invalid.
     * @throws UnauthorizedException - If the user does not have permission to create a permission.
     */
    public static function createPermission($data): Builder|Model
    {
        BaseBusiness::hasPermissionTo('create permissions');
        $data['team_id'] = getPermissionsTeamId();
        $validParams = Validator::validate($data, RolePermissionBusiness::createPermissionRules);
        return Permission::create($validParams);
    }

    /**
     * Copy the permissions from one role to another.
     * @param $role  - The role to copy the permissions from.
     * @param $newRole  - The role to copy the permissions to.
     * @return void
     */
    public static function copyRolePermissions($role, $newRole): void
    {
        $permissions = $role->permissions;
        foreach ($permissions as $permission) {
            $newRole->givePermissionTo($permission);
        }
    }

    /**
     * Copy the roles and permissions from one team to another.
     * @param $team  - The team to copy the roles and permissions from.
     * @param $newTeam  - The team to copy the roles and permissions to.
     * @return void
     */
    public static function copyTeamRolesAndPermissions($team, $newTeam): void
    {
        $roles = $team->roles;
        foreach ($roles as $role) {
            $newRole = Role::create(['name' => $role->name, 'team_id' => $newTeam->id]);
            RolePermissionBusiness::copyRolePermissions($role, $newRole);
        }
    }

    /**
     * Copy the default roles and permissions to a new team.
     * @param $newTeam  - The team to copy the roles and permissions to.
     * @return void
     */
    public static function copyDefaultRolesAndPermissions($newTeam): void
    {
        $defaultRoles = Role::where('team_id', 1)->get();
        foreach ($defaultRoles as $role) {
            setPermissionsTeamId($newTeam->id);
            $newRole = Role::create(['name' => $role->name, 'team_id' => $newTeam->id, 'guard_name' => $role->guard_name]);
//            $newRoleId = \DB::table('roles')->insertGetId([
//                'name' => $role->name,
//                'team_id' => $newTeam->id,
//                'guard_name' => $role->guard_name,
//                'created_at' => now(),
//                'updated_at' => now()
//            ]);
//            $newRole = Role::findByName($role->name);
            RolePermissionBusiness::copyRolePermissions($role, $newRole);
        }
    }
}
