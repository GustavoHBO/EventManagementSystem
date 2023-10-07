<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf_cnpj',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the events that the user has created.
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    /**
     * Get the teams that the user belongs to.
     * @return array
     */
    public function myTeams(): array
    {
        $modelPermissions = User::where('users.id', $this->id)
            ->where('model_type', User::class)
            ->join('model_has_permissions','model_has_permissions.model_id', '=', 'users.id')
            ->join('teams','teams.id', '=', 'model_has_permissions.team_id')
            ->groupBy('team_id')->get('team_id');
        $modelRoles = User::where('users.id', $this->id)
            ->where('model_type', User::class)
            ->join('model_has_roles','model_has_roles.model_id', '=', 'users.id')
            ->groupBy('team_id')->get('team_id');
        return Team::whereIn('id', $modelPermissions->pluck('team_id')
            ->concat($modelRoles->pluck('team_id'))
            ->unique())
            ->get()
            ->toArray();
    }

    /**
     * Get the teams that the user belongs to.
     * @return HasMany
     */
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class, 'user_id');
    }
}
