<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $userRole = auth()->user()->role->role_name ?? null;

        // Check if the user's role is in the allowed list passed to the middleware
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action. Only Administrators or Managers can access this area.');
    }
}