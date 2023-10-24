<?php

namespace App\Models;

use App\Http\Business\RolePermissionBusiness;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Permission\Models\Role;

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
            RolePermissionBusiness::copyDefaultRolesAndPermissions($model);
//            dd(Role::where('team_id', $model->id)->get()->toArray());
//            $role = Role::where('team_id', $model->id)->where('name', 'super admin')->first();
//            dd(Role::where('team_id', $model->id)->get()->toArray());
//            dd(Auth::user()->roles);
            $role = Role::where('name', 'super admin')->first();
            if ($role) {
                $user = Auth::user() ?? User::find(1);
                $user->assignRole('super admin');
            }
//            Auth::user()->assignRole('super admin');
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
     * Return all users of the team.
     * @return HasMany - Users of the team.
     */
    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Return all events of the team.
     * @return HasMany - Events of the team.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Return all coupons of the team.
     * @return HasMany - Coupons of the team.
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Define a relationship to retrieve lots through events associated with the team.
     * @return HasManyThrough - Lots associated with the team.
     */
    public function lots(): HasManyThrough
    {
        return $this->hasManyThrough(Lot::class, Event::class);
    }

    /**
     * Define a many-to-many relationship to retrieve sectors associated with the lot.
     * @return BelongsToMany - Sectors associated with the lot.
     */
    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class, 'lot_sector', 'lot_id', 'sector_id');
    }

    /**
     * Define a one-to-many relationship to retrieve ticket prices associated with the lot.
     * @return HasMany - Ticket prices associated with the lot.
     */
    public function ticketPrices(): HasMany
    {
        return $this->hasMany(TicketPrice::class, 'lot_id');
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, Event::class)->with('orderItems.ticket.ticketPrice');
    }

    /**
     * Get the orders for the team.
     * @return array|mixed[] - Orders for the team.
     */
    public function getOrders(): array
    {
        return $this->with('events.lots.ticketPrices.tickets.orderItem.order')->get()->pluck('events')->all();
    }
}
