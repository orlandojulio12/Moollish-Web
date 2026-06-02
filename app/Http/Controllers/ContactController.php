<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request, GoogleSheetsService $sheets)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email',
            'phone'                 => 'nullable|string',
            'user_type'             => 'required|in:Agricultor,Empresa agroindustrial',
            'sat2farm_expectations' => 'required|array|min:1',
            'improvement_goals'     => 'required|array|min:1',
            'message'               => 'nullable|string|max:1000',
        ], [
            'name.required'                  => 'Por favor, ingresa tu nombre.',
            'name.max'                       => 'El nombre no puede superar 255 caracteres.',
            'email.required'                 => 'El correo electrónico es obligatorio.',
            'email.email'                    => 'Por favor, ingresa un correo válido.',
            'user_type.required'             => 'Por favor, selecciona si eres agricultor o empresa.',
            'user_type.in'                   => 'Tipo de usuario no válido.',
            'sat2farm_expectations.required' => 'Por favor, selecciona al menos una expectativa.',
            'sat2farm_expectations.min'      => 'Por favor, selecciona al menos una expectativa.',
            'improvement_goals.required'     => 'Por favor, selecciona al menos un objetivo de mejora.',
            'improvement_goals.min'          => 'Por favor, selecciona al menos un objetivo de mejora.',
            'message.max'                    => 'El mensaje no puede superar 1000 caracteres.',
        ]);

        $data = [
            'name'                  => $validated['name'],
            'email'                 => $validated['email'],
            'phone'                 => $validated['phone'] ?? null,
            'user_type'             => $validated['user_type'],
            'sat2farm_expectations' => $validated['sat2farm_expectations'],
            'improvement_goals'     => $validated['improvement_goals'],
            'message'               => $validated['message'] ?? null,
        ];

        // Send email – log error but never block the form submission
        try {
            $to = env('CONTACT_EMAIL', 'info@moollish.com');
            Mail::to($to)->send(new ContactFormMail($data));
        } catch (\Exception $e) {
            Log::error('ContactForm mail error: ' . $e->getMessage());
        }

        // Log to Google Sheets – non-blocking
        try {
            $sheets->log($data);
        } catch (\Exception $e) {
            Log::error('ContactForm Google Sheets error: ' . $e->getMessage());
        }

        return back()->with('success', '¡Gracias! Un experto de Moollish se pondrá en contacto contigo pronto.');
    }
}
