<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function apiSendResetLinkEmail(Request $request)
    {
        // Validar el email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Enviar el enlace de reseteo
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        if ($response === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => trans($response),
            ], 200);
        }

        throw ValidationException::withMessages([
            'email' => [trans($response)],
        ]);
    }
}
