<?php

namespace App\Http\Controllers;

use App\Models\VisitaPrediosRiesgo;
use App\Models\Predios;
use Illuminate\Http\Request;

class VisitaPrediosRiesgoController extends Controller
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
     */  public function create($id)
    {
        $predioId = Predios::findOrFail($id)->id;
        $RiesgoExists = VisitaPrediosRiesgo::where('id_predio', $predioId)->exists();
        $visita = $RiesgoExists ? VisitaPrediosRiesgo::where('id_predio', $predioId)->first() : new VisitaPrediosRiesgo;
        return view('visita_predios_riesgo.create', compact('predioId', 'RiesgoExists', 'visita'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'id_predio' => 'required|integer|exists:predios,id',
            'enferm_baj_vigil' => 'nullable|string',
            'especie' => 'nullable|string',
            'num_anim_inspec' => 'nullable|string',
            'toma_muestras' => 'nullable|string',
            'toma_muestra_tipo' => 'nullable|string',
            'num_muestras' => 'nullable|integer'
        ]);

        // Crear un nuevo registro en la base de datos
         VisitaPrediosRiesgo::create($validatedData);

        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('Seccion4', ['id' => $request->id_predio])
        ->with('success', 'Visita de riesgo guardada exitosamente!');
    }

    public function update(Request $request, $id)
{
    // Validar los datos del formulario
    $validatedData = $request->validate([
        'id_predio' => 'required|integer|exists:predios,id',
        'enferm_baj_vigil' => 'nullable|string',
        'especie' => 'nullable|string',
        'num_anim_inspec' => 'nullable|string',
        'toma_muestras' => 'nullable|string',
        'toma_muestra_tipo' => 'nullable|string',
        'num_muestras' => 'nullable|integer'
    ]);

    // Buscar el registro existente y actualizar los datos
    $visita = VisitaPrediosRiesgo::findOrFail($id);
    $visita->update($validatedData);

    // Redirigir al usuario con un mensaje de éxito
    return redirect()->route('Seccion4', ['id' => $request->id_predio])
    ->with('success', 'Visita de riesgo actualizada exitosamente!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VisitaPrediosRiesgo $visitaPrediosRiesgo)
    {
        //
    }
}
