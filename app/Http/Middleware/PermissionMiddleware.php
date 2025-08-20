<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admin has all permissions
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user has the specific permission
        if (!$user->hasPermission($permission)) {
            abort(403, "Access denied. You don't have permission to: " . $this->getPermissionDescription($permission));
        }

        return $next($request);
    }

    /**
     * Get human-readable permission description
     */
    private function getPermissionDescription(string $permission): string
    {
        $descriptions = config('permissions.available_permissions', []);
        return $descriptions[$permission] ?? $permission;
    }
}