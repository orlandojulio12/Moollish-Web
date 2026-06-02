<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPuedeVender
{
    protected array $rolesPermitidos = [1, 3, 6];

    public function handle(Request $request, Closure $next): mixed
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->id_rol, $this->rolesPermitidos)) {
            return redirect()->route('marketplace.solicitar.upgrade')
                ->with('info', 'Para publicar productos necesitas una cuenta de proveedor.');
        }

        return $next($request);
    }
}
