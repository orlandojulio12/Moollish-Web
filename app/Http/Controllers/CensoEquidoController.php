<?php

namespace App\Http\Controllers;

use App\Models\CensoEquido;
use App\Models\Predios;
use Illuminate\Http\Request;

class CensoEquidoController extends Controller
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
                'men_6_mese_caballar' => 'nullable|integer',
                'seis_12_meses_caballar' => 'nullable|integer',
                'may_1_año_caballar' => 'nullable|integer',
                'total_caballar' => 'nullable|integer',
                'men_6_mese_mular' => 'nullable|integer',
                'seis_12_meses_mular' => 'nullable|integer',
                'may_1_año_mular' => 'nullable|integer',
                'total_mular' => 'nullable|integer',
                'men_6_mese_asnal' => 'nullable|integer',
                'seis_12_meses_asnal' => 'nullable|integer',
                'may_1_año_asnal' => 'nullable|integer',
                'total_asnal' => 'nullable|integer',
                'total_equidos' => 'nullable|integer',
            ]);
    
            CensoEquido::create($validatedData);
    
            return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
            ->with('success', 'Censo Équido creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al crear el Censo Équido: ' . $e->getMessage()]);
        }
    }
    

    public function show($id)
{
    // Obtener el id del predio asociado
    $predioId = Predios::findOrFail($id)->id;
    $censoExistente = CensoEquido::where('id_predio', $predioId)->exists();
    $CensoEquido = CensoEquido::where('id_predio', $predioId)->first(); 

    // Retornar la vista con los datos del censo équido y el id del predio
    return view('censo_equidos.show', compact('predioId','CensoEquido', 'censoExistente'));
}

public function update(Request $request, $id)
{
    try {
        $validatedData = $request->validate([
            'id_predio' => 'nullable|exists:predios,id',
            'men_6_mese_caballar' => 'nullable|integer',
            'seis_12_meses_caballar' => 'nullable|integer',
            'may_1_año_caballar' => 'nullable|integer',
            'total_caballar' => 'nullable|integer',
            'men_6_mese_mular' => 'nullable|integer',
            'seis_12_meses_mular' => 'nullable|integer',
            'may_1_año_mular' => 'nullable|integer',
            'total_mular' => 'nullable|integer',
            'men_6_mese_asnal' => 'nullable|integer',
            'seis_12_meses_asnal' => 'nullable|integer',
            'may_1_año_asnal' => 'nullable|integer',
            'total_asnal' => 'nullable|integer',
            'total_equidos' => 'nullable|integer',
        ]);

        $censoEquido = CensoEquido::findOrFail($id);
        $censoEquido->update($validatedData);

        return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
            ->with('success', 'Censo Équido actualizado exitosamente.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Error al actualizar el Censo Équido: ' . $e->getMessage()]);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CensoEquido $censoEquido)
    {
        //
    }
}
