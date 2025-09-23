<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformacionBgpRequest extends FormRequest
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
            'id_predio' => 'required|exists:predios,id', // Asegúrate de que 'predios' es la tabla correcta
            'id_tipos_bgp' => 'required|exists:tipos_informacion_bgp,id', // Asegúrate de que 'tipos_bgp' es la tabla correcta
            'tipo' => 'required',
            'estado' => 'required',
        ];
    }
}
