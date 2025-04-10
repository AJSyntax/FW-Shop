<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has the 'admin' role
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            // If not an admin, abort with 403 Forbidden
            abort(403, 'Unauthorized action.');
        }

        // If user is admin, proceed with the request
        return $next($request);
    }
}
