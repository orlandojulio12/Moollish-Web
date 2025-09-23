<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User; // Asegúrate de importar el modelo User

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => [
                'required',
                'string',
                'email',
                // Verifica que no haya otro usuario con el mismo correo
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    // Verificar si es un correo de Gmail y ya existe
                    if (str_ends_with($value, '@gmail.com')) {
                        if (User::where('email', $value)->exists()) {
                            $fail('El correo de Gmail ya está registrado.');
                        }
                    }
                },
            ],
            'tipo_documento' => 'required|string|in:nit,cedula,cedula_extranjeria,pasaporte',
            'password' => 'required|string',
            'documento' => 'required|string',
            'estado' => 'required|string',
            'profile_photo_path' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_rol' => 'required|string',
        ];
    }
}
