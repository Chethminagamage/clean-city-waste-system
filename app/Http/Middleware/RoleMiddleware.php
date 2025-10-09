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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // First check if user is authenticated
        if (!auth()->check()) {
            return redirect('/login');
        }
        
        if (!in_array(auth()->user()->role, $roles)) {
            // Log for debugging
            \Log::info('RoleMiddleware: Access denied', [
                'user_role' => auth()->user()->role,
                'required_roles' => $roles,
                'url' => $request->url()
            ]);
            
            // Return a proper 403 response instead of abort
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Forbidden. You do not have permission to access this resource.'], 403);
            }
            
            // For web requests, redirect with error message
            \Log::info('RoleMiddleware: Redirecting with error message');
            return redirect('/')->with('error', 'Access Denied: You do not have permission to access that page.');
        }
        return $next($request);
    }
}
