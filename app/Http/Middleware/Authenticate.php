<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            $uri = $request->path();
            
            // Redirect based on URL path
            if (Str::startsWith($uri, 'admin')) {
                return route('admin.login');
            }
            
            if (Str::startsWith($uri, 'collector')) {
                return route('collector.login');
            }
            
            // Default to resident login
            return route('login');
        }
        
        return null;
    }
}
