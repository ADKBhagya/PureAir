<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsWebMaster
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in and has role 'web_master'
        if (auth()->check() && auth()->user()->role === 'web_master') {
            return $next($request);
        }

        // If not authorized, show 403 page
        abort(403, 'Access denied. You are not authorized to view this page.');
    }
}
