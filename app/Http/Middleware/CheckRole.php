<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Mapa de alias → id_rol en la BD
     * 1 admin | 2 encuestador | 3 propietario | 4 veterinario
     * 5 comprador | 6 proveedor
     */
    protected array $rolesMap = [
        'admin'        => 1,
        'encuestador'  => 2,
        'propietario'  => 3,
        'veterinario'  => 4,
        'comprador'    => 5,
        'proveedor'    => 6,
    ];

    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Convertir alias a ids
        $rolesPermitidos = collect($roles)
            ->map(fn($alias) => $this->rolesMap[$alias] ?? null)
            ->filter()
            ->values()
            ->toArray();

        if (empty($rolesPermitidos)) {
            abort(403, 'Roles no definidos.');
        }

        if (!in_array($user->id_rol, $rolesPermitidos)) {
            // Si es una petición AJAX devolver JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autorizado.'], 403);
            }

            // Si tiene sesión activa redirigir con mensaje
            return redirect()
                ->route('dashboard')
                ->with('error', 'No tienes permiso para acceder a esa sección.');
        }

        return $next($request);
    }
}