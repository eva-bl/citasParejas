<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetUserLocaleAndTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Set locale
            if ($user->locale) {
                app()->setLocale($user->locale);
            }
            
            // Set timezone
            if ($user->timezone) {
                date_default_timezone_set($user->timezone);
                config(['app.timezone' => $user->timezone]);
            }
        }

        return $next($request);
    }
}
