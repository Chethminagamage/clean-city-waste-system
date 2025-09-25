<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (auth()->user()->role !== $role) {
            // Log for debugging
            \Log::info('CheckUserRole: Access denied', [
                'user_role' => auth()->user()->role,
                'required_role' => $role,
                'url' => $request->url(),
                'user_id' => auth()->user()->id
            ]);
            
            // Return a proper response instead of abort
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Forbidden. You do not have permission to access this resource.'], 403);
            }
            
            // For web requests, redirect with error message
            return redirect('/')->with('error', 'Access Denied: You do not have permission to access that page.');
        }

        return $next($request);
    }
}
