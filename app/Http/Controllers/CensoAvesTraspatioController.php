<?php

namespace App\Http\Controllers;

use App\Models\CensoAvesTraspatio;
use App\Models\Predios;
use App\Models\TipoAveTranspatio;
use Illuminate\Http\Request;

class CensoAvesTraspatioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'id_predio' => 'required|exists:predios,id',
                'id_tipo_ave_transp' => 'required|array',
                'id_tipo_ave_transp.*' => 'required|exists:tipo_ave_transpatio,id',
                'num_aves' => 'required|array',
                'num_aves.*' => 'required|integer|min:0',
                'edad' => 'nullable|array',
                'edad.*' => 'nullable|numeric|min:0',
                'precedencia_aves' => 'nullable|array',
                'precedencia_aves.*' => 'nullable|string|max:255',
                'observaciones' => 'nullable|array',
                'observaciones.*' => 'nullable|string|max:255',
            ]);

            // Crear un nuevo registro en la tabla 'censo_aves_traspatio'
            foreach ($validatedData['id_tipo_ave_transp'] as $index => $tipoAveTranspId) {
                CensoAvesTraspatio::create([
                    'id_predio' => $validatedData['id_predio'],
                    'id_tipo_ave_transp' => $tipoAveTranspId,
                    'num_aves' => $validatedData['num_aves'][$index],
                    'edad' => $validatedData['edad'][$index] ?? null,
                    'precedencia_aves' => $validatedData['precedencia_aves'][$index] ?? null,
                    'observaciones' => $validatedData['observaciones'][$index] ?? null,
                ]);
            }

            return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])->with('success', 'Censo de Aves de Traspatio creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al crear el Censo de Aves de Traspatio: ' . $e->getMessage()]);
        }
    }



    // Método show para mostrar un registro específico
    public function show($id)
    {
        $predioId = Predios::findOrFail($id)->id;
        $TipoAveTranspatio = TipoAveTranspatio::all();
        $censoExistente = CensoAvesTraspatio::where('id_predio', $predioId)->exists();
        $CensoAvesTraspatio = CensoAvesTraspatio::where('id_predio', $predioId)->get();

        return view('censo_aves_traspatio.show', compact('predioId', 'TipoAveTranspatio', 'CensoAvesTraspatio', 'censoExistente'));
    }

    public function update(Request $request, $id)
{
    try {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'id_predio' => 'required|exists:predios,id',
            'id_tipo_ave_transp' => 'required|array',
            'id_tipo_ave_transp.*' => 'required|exists:tipo_ave_transpatio,id',
            'num_aves' => 'required|array',
            'num_aves.*' => 'required|integer|min:0',
            'edad' => 'nullable|array',
            'edad.*' => 'nullable|numeric|min:0',
            'precedencia_aves' => 'nullable|array',
            'precedencia_aves.*' => 'nullable|string|max:255',
            'observaciones' => 'nullable|array',
            'observaciones.*' => 'nullable|string|max:255',
        ]);

        // Obtener el censo existente
        $censoExistente = CensoAvesTraspatio::where('id_predio', $validatedData['id_predio'])->get();

        // Actualizar los registros existentes
        foreach ($censoExistente as $index => $censo) {
            $censo->update([
                'id_tipo_ave_transp' => $validatedData['id_tipo_ave_transp'][$index],
                'num_aves' => $validatedData['num_aves'][$index],
                'edad' => $validatedData['edad'][$index] ?? null,
                'precedencia_aves' => $validatedData['precedencia_aves'][$index] ?? null,
                'observaciones' => $validatedData['observaciones'][$index] ?? null,
            ]);
        }

        return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])->with('success', 'Censo de Aves de Traspatio actualizado exitosamente.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Error al actualizar el Censo de Aves de Traspatio: ' . $e->getMessage()]);
    }
}

    public function destroy(CensoAvesTraspatio $censoAvesTraspatio)
    {
        //
    }
}
