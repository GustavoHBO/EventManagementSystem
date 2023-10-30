<?php

namespace App\Http\Middleware;

use Closure;
use Crypt;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $additionalData = Crypt::decrypt($request->header('Additional-Data'));
        $teamId = $additionalData['team_id'] ?? null;
        session(['team_id' => $teamId]);
        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
