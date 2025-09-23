<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropietarioRequest extends FormRequest
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
			'nombre_completo' => 'required|string',
			'tipo_doc' => 'required|string',
			'num_doc' => 'required|string',
			'genero' => 'required|string',
			'id_user' => 'required',
			'correo_electronico' => 'required|string',
			'telefono' => 'required',
        ];
    }
}
