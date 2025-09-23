<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrediosRequest extends FormRequest
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
        'id_propietario' => 'required|integer',
        'cod_predio' => 'required|string',
        'departamento' => 'required|string',
        'nombre_predio' => 'required|string',
        'municipio' => 'nullable|string',
        'vereda' => 'nullable|string',
        'forma_de_llegar' => 'required|string',
        'latitud' => 'nullable|string',
        'longitud' => 'nullable|string'
    ];
}

}
