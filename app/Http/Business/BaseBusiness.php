<?php

namespace App\Http\Business;

use Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class BaseBusiness
{

    /**
     * Check if the user has permission to do something.
     * @param  string  $permission  - Permission to be checked.
     * @throws UnauthorizedException - If the user does not have permission.
     */
    public static function hasPermissionTo(string $permission): void
    {
        if(!Auth::user()->hasPermissionTo($permission)){
            throw new UnauthorizedException(403, 'Você não tem permissão para realizar esta ação.');
        }
    }
}
