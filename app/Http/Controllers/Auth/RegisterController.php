<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Models\UserMembership;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Resend\Laravel\Facades\Resend;

class RegisterController extends Controller
{
    /**
     * Validación de datos
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
            'celular'           => ['required', 'string', 'max:20'],
            'id_rol'            => ['required', 'integer'],
            'documento'         => ['required', 'string', 'max:20', 'unique:users'],
            'tipo_documento'    => ['required', 'string', 'max:50'],
        ]);
    }

    /**
     * Si la validación falla, siempre devolvemos JSON (422)
     */
    protected function failedValidation(ValidatorContract $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422)
        );
    }

    /**
     * Crear usuario
     */
    protected function create(array $data)
    {
        return User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'celular'           => $data['celular'],
            'id_rol'            => $data['id_rol'],
            'documento'         => $data['documento'],
            'tipo_documento'    => $data['tipo_documento'],
            'password'          => Hash::make($data['password']),
            'verification_token' => Str::random(64),
        ]);
    }

    /**
     * Registro del usuario
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // Crear usuario
        event(new Registered($user = $this->create($request->all())));

        // Asignar membresía de prueba
        $planBasic = MembershipPlan::find(22);
        $free_trial_dias = 30;
        if ($planBasic) {
            $fechaInicio = now();
            $fechaExpiracion = $fechaInicio->copy()->addDays($free_trial_dias);

            UserMembership::create([
                'user_id'            => $user->id,
                'membership_plan_id' => $planBasic->id,
                'fecha_inicio'       => $fechaInicio,
                'fecha_expiracion'   => $fechaExpiracion,
                'estado'             => 'activo',
                'es_free_trial'      => true,
            ]);
        }

        // Enviar correo de verificación
        $emailSent = $this->sendVerificationEmail($user);

        // Logear al usuario
        Auth::login($user);

        // Respuesta en JSON
        return response()->json([
            'success'      => true,
            'redirect_url' => route('inicio'),
            'message'      => 'Registro exitoso. Por favor, verifica tu correo electrónico para completar la activación de tu cuenta.',
            'email_sent'   => $emailSent
        ]);
    }



    /**
     * Envía correo de verificación de email al usuario registrado
     */


    /**
     * Verifica el email del usuario a través del token enviado
     */
    public function verifyEmail(Request $request, $token)
    {
        // Buscar usuario con ese token de verificación
        $user = User::where('verification_token', $token)
                    ->where('email', $request->email)
                    ->first();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'El enlace de verificación no es válido.']);
        }

        // Marcar email como verificado y eliminar token
        $user->email_verified_at = now(); // Usar el campo nativo de Laravel
        $user->verification_token = null;
        $user->save();

        // Redireccionar a la página de confirmación de correo
        return redirect()->route('email.confirmation');
    }

    /**
     * Reenvía el correo de verificación al usuario.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendVerificationEmail(Request $request)
    {
        // Log inicial para marcar el inicio del proceso
        Log::info('Solicitud de reenvío de correo de verificación recibida', [
            'has_user' => $request->user() ? 'sí' : 'no',
            'email_in_request' => $request->has('email') ? $request->email : 'no',
            'headers' => $request->headers->all()
        ]);

        // Intentar obtener el usuario de la solicitud o buscar por email
        $user = null;

        if ($request->user()) {
            $user = $request->user();
            Log::info('Usuario obtenido de la sesión autenticada', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
        } elseif ($request->has('email')) {
            // Si no hay usuario autenticado, pero se proporcionó un email
            $user = User::where('email', $request->email)->first();
            if ($user) {
                Log::info('Usuario encontrado por email proporcionado', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            } else {
                Log::error('No se encontró usuario con el email proporcionado', [
                    'email' => $request->email
                ]);
                return response()->json([
                    'message' => 'No se encontró un usuario con ese correo electrónico.'
                ], 404);
            }
        }

        // Verificar si se obtuvo un usuario
        if (!$user) {
            Log::error('No se pudo identificar al usuario para reenviar el correo');
            return response()->json([
                'message' => 'No se pudo identificar al usuario. Por favor, inicia sesión nuevamente o proporciona un correo válido.'
            ], 401);
        }

        // Verificar si el usuario ya verificó su correo
        if ($user->email_verified_at) {
            Log::info('Reenvío cancelado: el correo ya está verificado', [
                'user_id' => $user->id,
                'email_verified_at' => $user->email_verified_at
            ]);

            return response()->json([
                'message' => 'Tu correo electrónico ya ha sido verificado.'
            ], 422);
        }

        // Generar un nuevo token de verificación si es necesario
        if (!$user->verification_token) {
            Log::info('Generando nuevo token de verificación', ['user_id' => $user->id]);

            $user->verification_token = Str::random(64);
            $user->save();

            Log::info('Nuevo token generado y guardado', [
                'user_id' => $user->id,
                'token_length' => strlen($user->verification_token)
            ]);
        } else {
            Log::info('Usando token existente', [
                'user_id' => $user->id,
                'token_exists' => !empty($user->verification_token),
                'token_length' => strlen($user->verification_token)
            ]);
        }

        // Enviar el correo de verificación
        try {
            Log::info('Intentando enviar correo de verificación', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            $emailSent = $this->sendVerificationEmail($user);

            Log::info('Resultado del envío de correo', [
                'user_id' => $user->id,
                'email_sent' => $emailSent
            ]);

            if ($emailSent) {
                return response()->json([
                    'message' => 'Correo de verificación enviado correctamente.'
                ]);
            } else {
                Log::error('Fallo al enviar correo - sendVerificationEmail devolvió false', [
                    'user_id' => $user->id
                ]);

                return response()->json([
                    'message' => 'No se pudo enviar el correo de verificación. Por favor, intenta más tarde.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Excepción al enviar correo de verificación', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al enviar el correo: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function sendVerificationEmail($user)
    {
        Log::info('Iniciando envío de correo de verificación', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        // Crear una URL con el token para verificación del correo
        $verificationUrl = URL::to('/verify-email/' . $user->verification_token . '?email=' . urlencode($user->email));
        Log::info('URL de verificación generada', [
            'url' => $verificationUrl,
            'url_length' => strlen($verificationUrl)
        ]);

        // Preparar el contenido HTML del correo
        $html = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta lang="es">
            <title>Verifica tu correo electrónico en Moollish</title>
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #ffffff;">
            <div style="max-width: 600px; margin: 0 auto; padding: 40px; border: 1px solid #e3e3e3; border-radius: 8px;">
                <!-- Logo centrado -->
                <div style="text-align: start;">
                    <img src="https://www.moollish.com/img/moollish.png" alt="Logo Moollish" style="height: 50px; margin-bottom: 20px;">
                </div>
                <!-- Título -->
                <h1 style="font-size: 24px; color: #333333; text-align: start; border-bottom: 1px solid #e3e3e3; padding-bottom: 20px;">
                    Verifica tu correo electrónico en Moollish
                </h1>
                <!-- Texto descriptivo -->
                <p style="font-size: 16px; color: #555555; text-align: start;">
                    ¡Gracias por registrarte en Moollish! Para completar tu registro y acceder a todas las funcionalidades, por favor verifica tu dirección de correo electrónico haciendo clic en el botón de abajo.
                </p>
                <br>

                <!-- Botón de confirmación -->
                <div style="text-align: start; margin: 30px 0; border-bottom: 1px solid #e3e3e3; padding-bottom: 20px;">
                    <a href="{$verificationUrl}" style="background-color: #E49B39; color: #ffffff; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-size: 16px;">
                        Verificar correo electrónico
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

        // Leer la configuración de Resend para verificar que esté correcta
        $resendApiKey = config('services.resend.api_key');
        $fromEmail = config('services.resend.from') ?? 'noreply@moollish.com';

        Log::info('Configuración de Resend', [
            'api_key_exists' => !empty($resendApiKey),
            'api_key_length' => $resendApiKey ? strlen($resendApiKey) : 0,
            'from_email' => $fromEmail
        ]);

        try {
            Log::info('Preparando envío de correo con Resend', [
                'to' => $user->email,
                'from' => $fromEmail
            ]);

            // Envío del correo utilizando Resend
            $response = Resend::emails()->send([
                'from' => $fromEmail,
                'to' => $user->email,
                'subject' => 'Verifica tu correo electrónico en Moollish',
                'html' => $html,
            ]);

            Log::info('Respuesta de Resend recibida', [
                'response' => $response
            ]);

            return true;
        } catch (\Exception $e) {
            // Capturar cualquier error durante el envío
            Log::error('Error al enviar correo con Resend', [
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);

            // Para facilitar debugging, vamos a intentar enviar con mail() nativo como fallback
            try {
                Log::info('Intentando enviar con mail() nativo como fallback');
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: Moollish <noreply@moollish.com>\r\n";

                $mailSent = mail($user->email, 'Verifica tu correo electrónico en Moollish', $html, $headers);

                Log::info('Resultado de mail() nativo', ['sent' => $mailSent ? 'sí' : 'no']);

                return $mailSent;
            } catch (\Exception $mailEx) {
                Log::error('Error al enviar con mail() nativo', [
                    'error' => $mailEx->getMessage()
                ]);
                return false;
            }
        }
    }

}
