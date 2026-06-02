<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => ['Las credenciales que ha ingresado son incorrectas.'],
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                dd('User authenticated with encrypted password');
                Auth::login($user);
                return $this->authenticated($request, $user);
            }

            if ($user->password === $request->password) {
                dd('User authenticated with plain password');
                Auth::login($user);
                $user->password = Hash::make($request->password);
                $user->save();
                return $this->authenticated($request, $user);
            }
        }

        dd('Authentication failed');
        return false;
    }


    protected function authenticated(Request $request, $user)
    {
        if ($user->role->name == 'admin') {
            return redirect()->route('dashboard');
        }

        // Viene del botón "Ingresar" del marketplace
        if ($request->input('from') === 'marketplace') {
            return redirect()->route('marketplace.panel');
        }

        if (!$user->membership || !$user->membership->isActive()) {
            return redirect()->route('membresias');
        }

        return redirect()->route('inicio');
    }



}

