<?php

namespace App\Http\Controllers;

use App\Models\CensoPorcino;
use App\Models\Predios;
use Illuminate\Http\Request;

class CensoPorcinoController extends Controller
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    
    try {
        $validatedData = $request->validate([
            'id_predio' => 'nullable|exists:predios,id',
            'lact_hast_30_dias' => 'nullable|integer',
            'precebo_31_a_60_dias' => 'nullable|integer',
            'lev_ceb_61_180_dias' => 'nullable|integer',
            'reempl_men_8_meses_h' => 'nullable|integer',
            'cria_men_8_meses_h' => 'nullable|integer',
            'macho_reprod_men_6_meses' => 'nullable|integer',
            'total_porcinos' => 'nullable|integer',
        ]);

        CensoPorcino::create($validatedData);

        return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
        ->with('success', 'Censo Porcino creado exitosamente.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Error al crear el Censo Porcino: ' . $e->getMessage()]);
    }
}

public function show($id)
{
    // Obtener el id del predio asociado
    $predioId = Predios::findOrFail($id)->id;

    // Obtener el censo porcino asociado al predio
       $censoExistente = CensoPorcino::where('id_predio', $predioId)->exists();
        $CensoPorcino = CensoPorcino::where('id_predio', $predioId)->first(); 

    // Retornar la vista con los datos del censo porcino y el id del predio
    return view('censo_porcinos.show', compact('predioId','censoExistente','CensoPorcino'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'id_predio' => 'nullable|exists:predios,id',
                'lact_hast_30_dias' => 'nullable|integer',
                'precebo_31_a_60_dias' => 'nullable|integer',
                'lev_ceb_61_180_dias' => 'nullable|integer',
                'reempl_men_8_meses_h' => 'nullable|integer',
                'cria_men_8_meses_h' => 'nullable|integer',
                'macho_reprod_men_6_meses' => 'nullable|integer',
                'total_porcinos' => 'nullable|integer',
            ]);
    
            $censoPorcino = CensoPorcino::findOrFail($id);
            $censoPorcino->update($validatedData);
    
            return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])->with('success', 'Censo Porcino actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al actualizar el Censo Porcino: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CensoPorcino $censoPorcino)
    {
        //
    }
}
