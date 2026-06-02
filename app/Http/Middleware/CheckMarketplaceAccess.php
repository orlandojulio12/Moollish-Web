<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMarketplaceAccess
{
    // Roles con acceso al marketplace: comprador(5), proveedor(6), propietario(3), admin(1)
    protected array $rolesPermitidos = [1, 3, 5, 6];

    public function handle(Request $request, Closure $next): mixed
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para continuar.');
        }

        if (!in_array($user->id_rol, $this->rolesPermitidos)) {
            return redirect()->back()
                ->with('error', 'Tu cuenta no tiene acceso al marketplace.');
        }

        return $next($request);
    }
}
