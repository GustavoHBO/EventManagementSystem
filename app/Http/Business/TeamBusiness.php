<?php

namespace App\Http\Business;

use App\Models\Team;
use App\Models\User;
use App\Notifications\UserTeamInviteNotification;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Str;
use Validator;

class TeamBusiness extends BaseBusiness
{
    /**
     * Get the teams that the user is a member of.
     * @return Collection - Teams that the user is a member of.
     */
    public static function getMyTeams(): Collection
    {
        return Auth::user()->teams;
    }

    /**
     * Create a new team instance and return it.
     * @param $data  - Data to validate.
     * @return Team - Created team.
     * @throws ValidationException - If the data is invalid.
     */
    public static function createTeam($data): Team
    {
        $rules = [
            'name' => 'required|string|max:255'
        ];
        $messages = [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'name.max' => 'The name field must not exceed 255 characters.'
        ];
        $validParams = Validator::validate($data, $rules, $messages);
        $validParams['user_id'] = Auth::user()->id;
        $team = Team::create($validParams);
        $actualTeamId = getPermissionsTeamId();
        setPermissionsTeamId($team->id);
        Auth::user()->assignRole('super admin');
        setPermissionsTeamId($actualTeamId);
        return $team;
    }

    /**
     * Invite a user to a team.
     * @param $data  - Data to validate.
     * @return void
     * @throws ValidationException - If the data is invalid.
     */
    public static function inviteUserToTeam($data): void
    {
        BaseBusiness::hasPermissionTo('team invite');
        $rules = [
            'roles' => 'required|array',
            'roles.*' => [
                'required',
                Rule::exists('roles', 'id')->where(function ($query) {
                    $query->where('team_id', getPermissionsTeamId());
                })
            ],
            'email' => 'required|email'
        ];
        $messages = [
            'roles.required' => 'The roles field is required.',
            'roles.array' => 'The roles field must be an array.',
            'roles.*.required' => 'The roles field must contain only valid roles.',
            'roles.*.exists' => 'The roles field must contain only valid roles.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email field must be a valid email address.'
        ];
        $validParams = Validator::validate($data, $rules, $messages);
        $user = User::where('email', $validParams['email'])->first();
        if (!$user) {
            $password = Str::password(8);
            $user = User::create([
                'email' => $validParams['email'],
                'name' => $validParams['email'],
                'password' => $password
            ]);
            $user->syncRoles($validParams['roles']);
        }

        foreach ($validParams['roles'] as $role) {
            $user->assignRole($role);
        }
        $team = Team::find(getPermissionsTeamId());
        $user->notify(new UserTeamInviteNotification($user, $team->name, $password ?? null));
    }

    /**
     * Remove user from team.
     * @param  array  $data  - Data to validate.
     * @return void - Nothing.
     * @throws ValidationException - If the data is invalid.
     */
    public static function removeUserFromTeam(array $data): void
    {
        BaseBusiness::hasPermissionTo('team withdraw');
        $rules = [
            'user_id' => 'required|exists:users,id'
        ];
        $messages = [
            'user_id.required' => 'The user id field is required.',
            'user_id.exists' => 'The user id field must be a valid user id.'
        ];
        $validParams = Validator::validate($data, $rules, $messages);

        if(Auth::user()->id == $validParams['user_id']){
            throw ValidationException::withMessages([
                'user_id' => 'Você não pode remover a si mesmo.'
            ]);
        }

        $user = User::findOrFail($validParams['user_id']);
        $roles = $user->roles->pluck('id')->toArray();
        foreach ($roles as $role) {
            $user->removeRole($role);
        }
        $permissions = $user->permissions->pluck('id')->toArray();
        foreach ($permissions as $permission) {
            $user->revokePermissionTo($permission);
        }
    }
}
