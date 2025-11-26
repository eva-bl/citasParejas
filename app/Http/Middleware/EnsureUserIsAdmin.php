<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Este middleware solo se ejecuta después de la autenticación
        // así que auth()->check() siempre será true aquí
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Acceso denegado. Solo administradores pueden acceder al panel.');
        }

        return $next($request);
    }
}

