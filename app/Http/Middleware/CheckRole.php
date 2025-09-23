<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Maneja la petición entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  Rol esperado (ej. "admin", "propietario", etc.)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Mapeo de alias de rol a id_rol en la base de datos.
        $rolesMap = [
            'admin'        => 1,
            'encuestador'  => 2,
            'propietario'  => 3,
        ];

        if (!isset($rolesMap[$role])) {
            abort(403, 'Rol no definido.');
        }

        // Usamos $user->id_rol (ya que la columna es id_rol)
        if ($user->id_rol != $rolesMap[$role]) {
            abort(403, 'Acción no autorizada.');
        }

        return $next($request);
    }
}
