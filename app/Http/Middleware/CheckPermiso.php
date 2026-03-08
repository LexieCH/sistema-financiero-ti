<?php

namespace App\Http\Middleware;

use App\Models\Permiso;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermiso
{
    public function handle(Request $request, Closure $next, string $modulo, ?string $accion = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para entrar al sistema.');
        }

        $usuario = Auth::user();

        if (!$usuario->rol) {
            return redirect()->route('dashboard')
                ->with('error', 'Tu usuario no tiene rol asignado. Pide apoyo al administrador.');
        }

        if ($usuario->rol->nombre === 'Admin') {
            return $next($request);
        }

        $accionFinal = $accion ?: $this->resolverAccion($request);

        $permiso = Permiso::query()
            ->where('rol_id', $usuario->rol_id)
            ->whereHas('modulo', function ($query) use ($modulo) {
                $query->where('nombre', $modulo)->where('estado', 'activo');
            })
            ->first();

        if (!$permiso || !$permiso->{$accionFinal}) {
            $accionAmigable = match ($accionFinal) {
                'lectura' => 'ver',
                'crear' => 'crear',
                'editar' => 'editar',
                'eliminar' => 'eliminar',
                default => 'usar',
            };

            return redirect()->route('dashboard')
                ->with('error', "No tienes permiso para {$accionAmigable} en el módulo {$modulo}.");
        }

        return $next($request);
    }

    private function resolverAccion(Request $request): string
    {
        $method = strtoupper($request->method());
        $actionMethod = $request->route()?->getActionMethod();

        if (in_array($actionMethod, ['index', 'show'])) {
            return 'lectura';
        }

        if (in_array($actionMethod, ['create', 'store'])) {
            return 'crear';
        }

        if (in_array($actionMethod, ['edit', 'update'])) {
            return 'editar';
        }

        if ($actionMethod === 'destroy') {
            return 'eliminar';
        }

        return match ($method) {
            'POST' => 'crear',
            'PUT', 'PATCH' => 'editar',
            'DELETE' => 'eliminar',
            default => 'lectura',
        };
    }
}
