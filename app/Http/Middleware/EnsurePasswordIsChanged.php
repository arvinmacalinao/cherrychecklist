<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Registered in the global 'web' middleware group (see Kernel), so every
 * authenticated page is covered without needing to remember to add it to
 * each new route group. No-ops instantly for guests and for anyone who
 * doesn't have a pending forced password change.
 */
class EnsurePasswordIsChanged
{
    /**
     * Routes a user with a pending forced password change must still be
     * able to reach — the change-password page itself and logout — so the
     * redirect below can never become a loop.
     */
    protected array $except = [
        'password.force-change',
        'password.force-change.store',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->must_change_password) {
            return $next($request);
        }

        if ($request->routeIs($this->except)) {
            return $next($request);
        }

        return redirect()->route('password.force-change');
    }
}
