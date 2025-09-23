<?php

namespace App\Http\Controllers;

use App\Models\CensoPez;
use App\Models\Predios;
use App\Models\TipoEspeciePeces;
use Illuminate\Http\Request;

class CensoPezController extends Controller
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
                'id_predio' => 'required|exists:predios,id',
                'id_tipo_esp_peces.*' => 'required|exists:tipo_especie_peces,id',
                'ovas.*' => 'nullable|integer',
                'alevinos.*' => 'nullable|integer',
                'engorde.*' => 'nullable|integer',
                'reproductores.*' => 'nullable|integer',
                'total_pez_especie.*' => 'nullable|integer',
                'total_peces' => 'nullable|integer', // Asegúrate de que esto se valide
            ]);
    
            // Recorrer cada fila de datos
            foreach ($validatedData['id_tipo_esp_peces'] as $index => $tipoEspeciePezId) {
                CensoPez::create([
                    'id_predio' => $validatedData['id_predio'],
                    'id_tipo_esp_peces' => $tipoEspeciePezId,
                    'ovas' => $validatedData['ovas'][$index] ?? 0,
                    'alevinos' => $validatedData['alevinos'][$index] ?? 0,
                    'engorde' => $validatedData['engorde'][$index] ?? 0,
                    'reproductores' => $validatedData['reproductores'][$index] ?? 0,
                    'total_pez_especie' => $validatedData['total_pez_especie'][$index] ?? 0,
                    'total_peces' => $validatedData['total_peces'], // Aquí se debe capturar el total general
                ]);
            }
    
            return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])->with('success', 'Censo de Peces creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al crear el Censo de Peces: ' . $e->getMessage()]);
        }
    }
    

    
    public function show($id)
{
    // Obtener el id del predio asociado
    $predioId = Predios::findOrFail($id)->id;

    // Obtener todas las especies de peces
    $TipoEspeciePeces = TipoEspeciePeces::all();

    // Verificar si ya existe un censo asociado al predio
    $censoExistente = CensoPez::where('id_predio', $predioId)->exists();

    // Obtener todos los registros del censo de peces para el predio
    $CensoPez = CensoPez::where('id_predio', $predioId)->get();

    return view('censo_peces.show', compact('predioId', 'TipoEspeciePeces', 'CensoPez', 'censoExistente'));
}
    
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CensoPez $censoPez)
    {
        //
    }
}
