<?php

namespace App\Http\Controllers\API;

use App\Http\Business\BaseBusiness;
use App\Http\Business\RolePermissionBusiness;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Validator;

class RolePermissionController extends Controller
{

    /**
     * Display a listing of the roles.
     * @return JsonResponse - Roles data.
     * @throws UnauthorizedException - If the user does not have permission to view roles.
     */
    public function getRoles(): JsonResponse
    {
        BaseBusiness::hasPermissionTo('role list');
        $roles = Role::all()->where('team_id', getPermissionsTeamId());
        return $this->sendSuccessResponse($roles, 'Roles recuperados com sucesso!');
    }

    /**
     * Create a new Role instance and return it.
     * @throws ValidationException - If the data is invalid.
     * @throws UnauthorizedException - If the user does not have permission to create a role.
     */
    public function createRole(Request $request): JsonResponse
    {
        $role = RolePermissionBusiness::createRole($request->all());
        return $this->sendSuccessResponse($role, 'Role criado com sucesso!', Response::HTTP_CREATED);
    }

    /**
     * Delete the specified role.
     * @param $id  - Role ID.
     * @return JsonResponse - Role data.
     * @throws UnauthorizedException - If the user does not have permission to delete a role.
     */
    public function deleteRole($id): JsonResponse
    {
        BaseBusiness::hasPermissionTo('role delete');
        $role = Role::findById($id);
        $role->delete();
        return $this->sendSuccessResponse($role, 'Role deletado com sucesso!');
    }

    /**
     * Display a listing of the permissions.
     * @return JsonResponse - Permissions data.
     * @throws UnauthorizedException - If the user does not have permission to view permissions.
     */
    public function getPermissions(): JsonResponse
    {
        BaseBusiness::hasPermissionTo('permission list');
        $permissions = Permission::all();
        return $this->sendSuccessResponse($permissions, 'Permissions recuperados com sucesso!');
    }


    /**
     * Delete the specified permission.
     * @param $id  - Permission ID.
     * @return JsonResponse - Permission data.
     * @throws UnauthorizedException - If the user does not have permission to delete a permission.
     */
    public function deletePermission($id): JsonResponse
    {
        BaseBusiness::hasPermissionTo('permission delete');
        $permission = Permission::findById($id);
        $permission->delete();
        return $this->sendSuccessResponse($permission, 'Permission deletado com sucesso!');
    }

    /**
     * Attach permissions to a role and return it.
     * @param  Request  $request  - Request data.
     * @param $roleId  - Role ID.
     * @return JsonResponse - Role data.
     */
    public function attachPermissions(Request $request, $roleId): JsonResponse
    {
        BaseBusiness::hasPermissionTo('attach permissions to roles');
        $role = Role::findById($roleId);
        $role->syncPermissions($request->permissions);
        return $this->sendSuccessResponse($role, 'Permissions anexadas ao role com sucesso!');
    }

    /**
     * Detach permissions from a role and return it.
     * @param  Request  $request
     * @param $roleId  - Role ID.
     * @return JsonResponse - Role data.
     * @throws ValidationException - If the data is invalid.
     */
    public function detachPermissions(Request $request, $roleId): JsonResponse
    {
        BaseBusiness::hasPermissionTo('detach permissions from roles');
        $rules = [
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,id'
        ];
        $validParams = Validator::validate($request->all(), $rules);
        $role = Role::findById($roleId);
        $role->revokePermissionTo($validParams);
        return $this->sendSuccessResponse($role, 'Permissions desanexadas do role com sucesso!');
    }


    /**
     * Detach roles from a user and return it.
     * @param  Request  $request  - Request data.
     * @return JsonResponse - User data.
     * @throws ValidationException - If the data is invalid.
     */
    public function detachRoles(Request $request): JsonResponse
    {
        BaseBusiness::hasPermissionTo('detach roles from users');
        $rules = [
            'roles' => 'required|array',
            'roles.*' => 'required|exists:roles,id',
            'user_email' => 'required|email|exists:users,email'
        ];
        $messages = [
            'roles.required' => 'The roles field is required.',
            'roles.array' => 'The roles field must be an array.',
            'roles.*.required' => 'The roles field must contain only valid roles.',
            'roles.*.exists' => 'The roles field must contain only valid roles.',
            'user_email.required' => 'The user email field is required.',
            'user_email.email' => 'The user email field must be a valid email address.',
            'user_email.exists' => 'The selected user email does not exist in the database.'
        ];
        $validParams = Validator::validate($request->all(), $rules, $messages);
        $user = User::where('email', $validParams['user_email'])->first();
        foreach ($validParams['roles'] as $role) {
            $user->removeRole($role);
        }
        return $this->sendSuccessResponse($user, 'Roles desanexadas do usuário com sucesso!');
    }
}
