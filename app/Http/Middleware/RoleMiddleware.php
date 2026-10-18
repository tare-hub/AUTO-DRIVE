<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Admin has superuser access to all operational areas
        if ($user->role === 'admin') {
            return $next($request);
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Unauthorized access to this restricted automotive workspace.');
        }

        return $next($request);
    }
}
