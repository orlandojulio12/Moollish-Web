<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureActiveMembership
{
    /**
     * Maneja la petición entrante.
     *
     * Si el usuario no tiene una membresía activa, se redirige a la ruta de membresías.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        // Verifica que el usuario tenga una membresía y que esté activa
        if (!$user || !$user->membership || !$user->membership->isActive()) {
            return redirect()->route('membresias')
                ->with('error', 'Debes tener una membresía activa para acceder a esta sección.');
        }
        return $next($request);
    }
}
