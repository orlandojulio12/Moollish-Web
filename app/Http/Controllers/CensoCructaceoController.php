<?php

namespace App\Http\Controllers;

use App\Models\CensoCructaceo;
use App\Models\Predios;
use App\Models\TipoEspecieCrustaceos;
use Illuminate\Http\Request;

class CensoCructaceoController extends Controller
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
        $validatedData = $request->validate([
            'id_predio' => 'nullable|exists:predios,id',
            'id_tipo_esp_cructac.*' => 'nullable|exists:tipo_especie_cructaceos,id',
            'nauplinos.*' => 'nullable|integer',
            'larvicultura.*' => 'nullable|integer',
            'engorde.*' => 'nullable|integer',
            'reproductores.*' => 'nullable|integer',
            'total_especie_cructacio.*' => 'nullable|integer',
            'total_cructaceos' => 'nullable|integer', // Aquí se valida el total de cructáceos
        ]);

        // Recorrer cada fila de datos
        foreach ($validatedData['id_tipo_esp_cructac'] as $index => $tipoEspecieCructacId) {
            CensoCructaceo::create([
                'id_predio' => $validatedData['id_predio'],
                'id_tipo_esp_cructac' => $tipoEspecieCructacId,
                'nauplinos' => $validatedData['nauplinos'][$index] ?? 0,
                'larvicultura' => $validatedData['larvicultura'][$index] ?? 0,
                'engorde' => $validatedData['engorde'][$index] ?? 0,
                'reproductores' => $validatedData['reproductores'][$index] ?? 0,
                'total_especie_cructacio' => $validatedData['total_especie_cructacio'][$index] ?? 0,
                'total_cructaceos' => $validatedData['total_cructaceos'], // Aquí se captura el total general
            ]);
        }

        return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])->with('success', 'Censo de Cructáceos creado exitosamente.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Error al crear el Censo de Cructáceos: ' . $e->getMessage()]);
    }
}


public function show($id)
{
    // Obtener el id del predio asociado
    $predioId = Predios::findOrFail($id)->id;
    
    // Obtener todas las especies de crustáceos
    $TipoEspecieCructaceos = TipoEspecieCrustaceos::all();

    // Verificar si ya existe un censo asociado al predio
    $censoExistente = CensoCructaceo::where('id_predio', $predioId)->exists();

    // Obtener todos los registros del censo de crustáceos para el predio
    $CensoCructaceo = CensoCructaceo::where('id_predio', $predioId)->get();

    return view('censo_cructaceos.show', compact('predioId', 'TipoEspecieCructaceos', 'CensoCructaceo', 'censoExistente'));
}


public function update(Request $request, $id_predio)
{
    try {
        $validatedData = $request->validate([
            'id_predio' => 'required|exists:predios,id',
            'id_tipo_esp_cructac.*' => 'required|exists:tipo_especie_cructaceos,id',
            'nauplinos.*' => 'nullable|integer',
            'larvicultura.*' => 'nullable|integer',
            'engorde.*' => 'nullable|integer',
            'reproductores.*' => 'nullable|integer',
            'total_especie_cructacio.*' => 'nullable|integer',
            'total_cructaceos' => 'nullable|integer',
        ]);

        // Recorrer cada fila de datos y actualizar
        foreach ($validatedData['id_tipo_esp_cructac'] as $index => $tipoEspecieCructacId) {
            // Buscar el registro del censo para el tipo de especie y predio
            $censoCructaceo = CensoCructaceo::where('id_predio', $validatedData['id_predio'])
                ->where('id_tipo_esp_cructac', $tipoEspecieCructacId)
                ->firstOrFail();
            
            // Actualizar los datos del registro
            $censoCructaceo->update([
                'nauplinos' => $validatedData['nauplinos'][$index] ?? 0,
                'larvicultura' => $validatedData['larvicultura'][$index] ?? 0,
                'engorde' => $validatedData['engorde'][$index] ?? 0,
                'reproductores' => $validatedData['reproductores'][$index] ?? 0,
                'total_especie_cructacio' => $validatedData['total_especie_cructacio'][$index] ?? 0,
                'total_cructaceos' => $validatedData['total_cructaceos'],
            ]);
        }

        return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
            ->with('success', 'Censo de Crustáceos actualizado exitosamente.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Error al actualizar el Censo de Crustáceos: ' . $e->getMessage()]);
    }
}



    public function destroy(CensoCructaceo $censoCructaceo)
    {
        //
    }
}
