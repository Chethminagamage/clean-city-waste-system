<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAfterSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Process the request
        $response = $next($request);

        // If the response is a redirect to the home page after a session timeout
        if ($response->getStatusCode() === 302 && $response->headers->get('Location') === url('/')) {
            // Check which guard was being used based on URL paths
            $path = $request->path();
            
            if (str_starts_with($path, 'admin')) {
                return redirect()->route('admin.login');
            }
            
            if (str_starts_with($path, 'collector')) {
                return redirect()->route('collector.login');
            }
            
            // Default to resident login for all other paths
            return redirect()->route('login');
        }

        return $response;
    }
}
