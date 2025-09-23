<?php

namespace App\Http\Controllers;

use App\Models\CensoBufalino;
use App\Models\Predios;
use Illuminate\Http\Request;

class CensoBufalinoController extends Controller
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
                'total_bufalinos' => 'nullable|integer',
            ]);

            CensoBufalino::create($validatedData);

            return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
                ->with('success', 'Censo Bufalino creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al crear el Censo Bufalino: ' . $e->getMessage()]);
        }
    }


    public function show($id)
    {
        $predioId = Predios::findOrFail($id)->id;
        $censoExistente = CensoBufalino::where('id_predio', $predioId)->exists();
        $CensoBufalino = CensoBufalino::where('id_predio', $predioId)->first(); 

        return view('censo_bufalinos.show', compact('predioId', 'CensoBufalino', 'censoExistente'));
    }


    public function update(Request $request, $id)
    {
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
            'total_bufalinos' => 'nullable|integer',
        ]);

        try {
            // Encontrar el censo por ID
            $censo = CensoBufalino::findOrFail($id);

            // Actualizar los datos validados
            $censo->update($validatedData);

            // Redirigir con un mensaje de éxito
            return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])->with('success', 'Censo Bufalino actualizado exitosamente.');
        } catch (\Exception $e) {
            // En caso de error, redirigir con un mensaje de error
            return redirect()->route('censo_bufalinos.show')->with('error', 'Error al actualizar el Censo Bufalino: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $censo = CensoBufalino::findOrFail($id);
        $censo->delete();

        return redirect()->route('censo_bufalinos.index')->with('success', 'Censo Bufalino eliminado exitosamente.');
    }
}
