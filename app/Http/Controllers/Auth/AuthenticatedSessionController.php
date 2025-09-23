<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra la vista de login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Maneja la solicitud de inicio de sesión.
     */
     public function store(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        // Solo regenerar la sesión si NO es una solicitud API
        if (!$request->expectsJson()) {
            $request->session()->regenerate();
        }

        $user = Auth::user();

        // Verificar membresía activa
        if (!$user->membership || !$user->membership->isActive()) {
            $message = 'Tu membresía ha terminado o no está activa.';

            return $request->expectsJson()
                ? response()->json(['message' => $message, 'status' => 'error'], 403)
                : redirect()->route('membresias')->with('error', $message);
        }

        $redirectTo = match ($user->role->name) {
            'encuestador', 'propietario' => route('inicio'),
            default => route('dashboardAdmin'),
        };

        $successMessage = 'Inicio de sesión exitoso';

        return $request->expectsJson()
            ? response()->json([
                'message' => $successMessage,
                'user' => $user,
                //'redirect_to' => $redirectTo,
                'status' => 'success'
            ])
            : redirect()->intended($redirectTo)->with('success', $successMessage);
    }

    $errorMessage = 'Las credenciales proporcionadas no coinciden con nuestros registros.';

    return $request->expectsJson()
        ? response()->json(['message' => $errorMessage, 'status' => 'error'], 401)
        : back()->with('error', $errorMessage);
}

    /**
     * Cierra la sesión del usuario.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Redirige directamente al login
        return redirect('/login');
    }
}
