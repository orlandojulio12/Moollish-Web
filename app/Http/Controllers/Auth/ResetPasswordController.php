<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Resend\Laravel\Facades\Resend;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Envía el correo de restablecimiento de contraseña usando Resend
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'Por favor, ingresa una dirección de correo electrónico válida.'
        ]);

        $email = $request->email;

        // Verificar si el usuario existe
        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'email' => ['El correo que ingresaste no se encuentra registrado en Moollish']
                ]
            ], 422);
        }

        // Crear un token único válido por 48 horas
        $token = Str::random(64);

        // Almacenar token en la base de datos
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Crear la URL con el token para restablecer contraseña
        $resetUrl = URL::to('/reset-password/' . $token . '?email=' . urlencode($email));

        // ✅ Manteniendo tu HTML con estilos
        $html = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta lang="es">
        <title>Restablece tu contraseña en Moollish</title>
    </head>
    <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #ffffff;">
        <div style="max-width: 600px; margin: 0 auto; padding: 40px; border: 1px solid #e3e3e3; border-radius: 8px;">
            <!-- Logo centrado -->
            <div style="text-align: start;">
                <img src="https://www.moollish.com/img/moollish.png" alt="Logo Moollish" style="height: 50px; margin-bottom: 20px;">
            </div>
            <!-- Título -->
            <h1 style="font-size: 24px; color: #333333; text-align: start; border-bottom: 1px solid #e3e3e3; padding-bottom: 20px;">
                Restablece tu contraseña en Moollish
            </h1>
            <!-- Texto descriptivo -->
            <p style="font-size: 16px; color: #555555; text-align: start;">
                Has solicitado restablecer tu contraseña. Haz clic en el botón de abajo para crear una nueva contraseña.
                Este enlace es válido durante 48 horas.
            </p>
            <br>

            <!-- Botón de confirmación -->
            <div style="text-align: start; margin: 30px 0; border-bottom: 1px solid #e3e3e3; padding-bottom: 20px;">
                <a href="{$resetUrl}" style="background-color: #E49B39; color: #ffffff; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-size: 16px;">
                    Restablecer Contraseña
                </a>
            </div>
            <!-- Footer -->
            <p style="font-size: 15px; color: #000; text-align: start; margin-top: 40px;">
                <span style="color: #999999;">
                    Este es un mensaje automático, por favor no responder este correo electrónico.
                </span>
            </p>
        </div>
    </body>
    </html>
    HTML;

        try {
            // Envío con Resend
            Resend::emails()->send([
                'from' => 'noreply@moollish.com',
                'to' => $email,
                'subject' => 'Restablece tu contraseña en Moollish',
                'html' => $html,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Hemos enviado un correo con instrucciones para restablecer tu contraseña.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'email' => ['No pudimos enviar el correo. Por favor, intenta más tarde.']
                ]
            ], 500);
        }
    }

    /**
     * Restablece la contraseña del usuario
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]+/'
            ],
        ], [
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos un carácter especial.',
            'password.confirmed' => 'Las contraseñas no coinciden.'
        ]);

        $email = $request->email;
        $token = $request->token;
        $password = $request->password;

        // Verificar el token
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$tokenData || !Hash::check($token, $tokenData->token)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Este enlace de restablecimiento no es válido.']);
        }

        // Verificar que el token no tenga más de 48 horas
        if (Carbon::parse($tokenData->created_at)->addHours(48)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('login')
                ->withErrors(['email' => 'Este enlace de restablecimiento ha expirado. Por favor, solicita uno nuevo.']);
        }

        // Actualizar la contraseña del usuario
        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'No hemos podido encontrar un usuario con esa dirección de correo.']);
        }

        DB::table('users')
            ->where('email', $email)
            ->update(['password' => Hash::make($password)]);

        // Eliminar el token usado
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Redirigir al login con mensaje de éxito
        return redirect()->route('login')
            ->with('success', '¡Tu contraseña ha sido restablecida con éxito! Ya puedes iniciar sesión con tu nueva contraseña.');
    }
}
