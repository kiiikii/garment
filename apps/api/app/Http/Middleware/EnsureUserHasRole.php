<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $userRole = $user->role instanceof Role ? $user->role->value : (string) $user->role;

        if (! in_array($userRole, $roles, true)) {
            return response()->json([
                'message' => 'Forbidden. You do not have permission to access this resource.',
            ], 403);
        }

        return $next($request);
    }
}
