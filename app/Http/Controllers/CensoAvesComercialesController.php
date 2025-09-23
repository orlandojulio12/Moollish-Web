<?php

namespace App\Http\Controllers;

use App\Models\CensoAvesComerciales;
use App\Models\Predios;
use App\Models\TipoAveComercial;
use Illuminate\Http\Request;

class CensoAvesComercialesController extends Controller
{

    
        public function store(Request $request)
        {
            try {
                $validatedData = $request->validate([
                    'id_predio' => 'required|exists:predios,id',
                    'id_tipo_ave_comercial.*' => 'required|exists:tipo_ave_comcercial,id',
                    'linea.*' => 'nullable|string|max:255',
                    'num_aves.*' => 'required|integer|min:0',
                    'edad.*' => 'nullable|numeric|min:0',
                    'num_galones.*' => 'nullable|integer|min:0',
                    'area_galones.*' => 'nullable|string|max:255',
                    'densidad.*' => 'nullable|numeric|min:0',
                    'tiemp_descan_lotes.*' => 'nullable|string|max:255',
                    'procedencia_aves.*' => 'nullable|string|max:255',
                ]);
    
                // Recorrer cada fila de datos
                foreach ($validatedData['id_tipo_ave_comercial'] as $index => $tipoAveComercialId) {
                    CensoAvesComerciales::create([
                        'id_predio' => $validatedData['id_predio'],
                        'id_tipo_ave_comercial' => $tipoAveComercialId,
                        'linea' => $validatedData['linea'][$index] ?? null,
                        'num_aves' => $validatedData['num_aves'][$index] ?? 0,
                        'edad' => $validatedData['edad'][$index] ?? 0,
                        'num_galones' => $validatedData['num_galones'][$index] ?? 0,
                        'area_galones' => $validatedData['area_galones'][$index] ?? null,
                        'densidad' => $validatedData['densidad'][$index] ?? 0,
                        'tiemp_descan_lotes' => $validatedData['tiemp_descan_lotes'][$index] ?? null,
                        'procedencia_aves' => $validatedData['procedencia_aves'][$index] ?? null,
                    ]);
                }

            return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])->with('success', 'Censo de Aves Comerciales creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al crear el Censo de Aves Comerciales: ' . $e->getMessage()]);
        }
    }

    // Método show para mostrar un registro específico
    public function show($id)
    {
        $predioId = Predios::findOrFail($id)->id;
        
        $TipoAveComercial = TipoAveComercial::all();

        $censoExistente = CensoAvesComerciales::where('id_predio', $predioId)->exists();

        $CensoAvesComerciales = CensoAvesComerciales::where('id_predio', $predioId)->get();

        return view('censo_aves_comerciales.show', compact('predioId', 'TipoAveComercial', 'CensoAvesComerciales', 'censoExistente'));
    }
    

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'id_predio' => 'required|exists:predios,id',
                'id_tipo_ave_comercial.*' => 'required|exists:tipo_ave_comcercial,id',
                'linea.*' => 'nullable|string|max:255',
                'num_aves.*' => 'required|integer|min:0',
                'edad.*' => 'nullable|numeric|min:0',
                'num_galones.*' => 'nullable|integer|min:0',
                'area_galones.*' => 'nullable|string|max:255',
                'densidad.*' => 'nullable|numeric|min:0',
                'tiemp_descan_lotes.*' => 'nullable|string|max:255',
                'procedencia_aves.*' => 'nullable|string|max:255',
            ]);
    
            // Obtener los registros existentes del censo
            $censosExistentes = CensoAvesComerciales::where('id_predio', $validatedData['id_predio'])->get();
    
            // Actualizar cada registro del censo
            foreach ($censosExistentes as $index => $censo) {
                $censo->update([
                    'linea' => $validatedData['linea'][$index] ?? null,
                    'num_aves' => $validatedData['num_aves'][$index] ?? 0,
                    'edad' => $validatedData['edad'][$index] ?? 0,
                    'num_galones' => $validatedData['num_galones'][$index] ?? 0,
                    'area_galones' => $validatedData['area_galones'][$index] ?? null,
                    'densidad' => $validatedData['densidad'][$index] ?? 0,
                    'tiemp_descan_lotes' => $validatedData['tiemp_descan_lotes'][$index] ?? null,
                    'procedencia_aves' => $validatedData['procedencia_aves'][$index] ?? null,
                ]);
            }
    
            return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])->with('success', 'Censo de Aves Comerciales actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al actualizar el Censo de Aves Comerciales: ' . $e->getMessage()]);
        }
    }
    
}
