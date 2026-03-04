<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRol
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado');
        }

        $usuario = auth()->user();

        if (!in_array($usuario->rol->nombre, $roles)) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}