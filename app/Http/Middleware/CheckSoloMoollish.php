<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSoloMoollish
{
    protected array $rolesPermitidos = [1, 2, 3, 4]; // admin, encuestador, propietario, veterinario

    public function handle(Request $request, Closure $next): mixed
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->id_rol, $this->rolesPermitidos)) {
            return redirect()->route('marketplace.home')
                ->with('info', 'Esta sección es exclusiva para usuarios de Moollish. '
                    . 'Actualiza tu membresía para acceder.');
        }

        return $next($request);
    }
}
