<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperTeam
 */
class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name'
    ];

    /**
     * The attributes that are mass assignable.
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        // here assign this team to a global user with global default role
        self::created(function ($model) {
            // temporary: get session team_id for restore at end
            $session_team_id = getPermissionsTeamId();
            // set actual new team_id to package instance
            setPermissionsTeamId($model);
            // get the admin user and assign roles/permissions on new team model
            User::find(1)->assignRole('super-admin');
            // restore session team_id to package instance using temporary value stored above
            setPermissionsTeamId($session_team_id);
        });
    }

    /**
     * Get the user that created the team.
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Add a user to the team.
     * @param $userId
     * @return void
     */
    public function addUser($userId): void
    {
        $this->members()->attach($userId);
    }

    /**
     * Retorna os membros do time.
     * @return BelongsToMany
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id');
    }
}
