<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasCouple
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Allow access to couple creation/joining routes
        if ($request->routeIs('couple.*') || $request->routeIs('register.*') || $request->routeIs('login.*')) {
            return $next($request);
        }

        if (!$user->hasCouple()) {
            return redirect()->route('couple.setup');
        }

        return $next($request);
    }
}




