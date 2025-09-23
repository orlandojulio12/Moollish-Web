<?php

namespace App\Http\Controllers;

use App\Models\CensoBovino;
use App\Models\Predios;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CensoBovinoController extends Controller
{
    public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'id_predio' => 'nullable|exists:predios,id',
            'men_3_meses_h' => 'nullable|integer',
            'tres_a_9_meses_h' => 'nullable|integer',
            'nueve_a_12_meses_h' => 'nullable|integer',
            'uno_a_2_años_h' => 'nullable|integer',
            'dos_a_3_años_h' => 'nullable|integer',
            'tres_a_5_años_h' => 'nullable|integer',
            'may_5_años_h' => 'nullable|integer',
            'total_hembras' => 'nullable|integer',
            'men_3_meses_m' => 'nullable|integer',
            'tres_a_9_meses_m' => 'nullable|integer',
            'nueve_a_12_meses_m' => 'nullable|integer',
            'uno_a_2_años_m' => 'nullable|integer',
            'dos_a_3_años_m' => 'nullable|integer',
            'may_3_años' => 'nullable|integer',
            'total_machos' => 'nullable|integer',
            'total_bovinos' => 'nullable|integer',
        ]);

        // Crear el registro en la base de datos
        $censoBovino = CensoBovino::create($validatedData);

        // Redirigir a la vista Seccion6 con el id_predio
        return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
                         ->with('success', 'Censo Bovino creado exitosamente.');

    } catch (Exception $e) {
        return back()->withErrors(['error' => 'Error al crear el censo bovino: ' . $e->getMessage()]);
    }
}
   

public function show($id)
{
    $predioId = Predios::findOrFail($id)->id;

    // Verificar si ya existe un registro en la tabla CensoBovino para este predio
    $censoExistente = CensoBovino::where('id_predio', $predioId)->exists();

    // Obtener el censo existente si lo hay
    $censoBovino = CensoBovino::where('id_predio', $predioId)->first(); 

    // Pasar el censoBovino y otras variables a la vista
    return view('censo_bovinos.show', compact('predioId', 'censoExistente', 'censoBovino'));
}

    
public function update(Request $request, $id)
{
    try {
        // Validación de los datos
        $validatedData = $request->validate([
            'id_predio' => 'nullable|exists:predios,id',
            'men_3_meses_h' => 'nullable|integer',
            'tres_a_9_meses_h' => 'nullable|integer',
            'nueve_a_12_meses_h' => 'nullable|integer',
            'uno_a_2_años_h' => 'nullable|integer',
            'dos_a_3_años_h' => 'nullable|integer',
            'tres_a_5_años_h' => 'nullable|integer',
            'may_5_años_h' => 'nullable|integer',
            'total_hembras' => 'nullable|integer',
            'men_3_meses_m' => 'nullable|integer',
            'tres_a_9_meses_m' => 'nullable|integer',
            'nueve_a_12_meses_m' => 'nullable|integer',
            'uno_a_2_años_m' => 'nullable|integer',
            'dos_a_3_años_m' => 'nullable|integer',
            'may_3_años' => 'nullable|integer',
            'total_machos' => 'nullable|integer',
            'total_bovinos' => 'nullable|integer',
        ]);

        // Encontrar el registro existente de CensoBovino y actualizarlo
        $censoBovino = CensoBovino::findOrFail($id);
        $censoBovino->update($validatedData);

        // Redirigir a la vista 'Seccion6' con un mensaje de éxito
        return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
                         ->with('success', 'Censo Bovino actualizado exitosamente.');

    } catch (ModelNotFoundException $e) {
        return redirect()->back()->withErrors(['error' => 'Censo bovino no encontrado.']);
    } catch (Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Error al actualizar el censo bovino: ' . $e->getMessage()]);
    }
}

}
