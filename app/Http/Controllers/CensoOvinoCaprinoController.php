<?php

namespace App\Http\Controllers;

use App\Models\CensoOvinoCaprino;
use App\Models\Predios;
use Illuminate\Http\Request;

class CensoOvinoCaprinoController extends Controller
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
            'men_6_meses_h_ovi' => 'nullable|integer',
            'may_6_meses_h_ovi' => 'nullable|integer',
            'total_hembras_ovinas' => 'nullable|integer',
            'men_6_meses_m_ovi' => 'nullable|integer',
            'may_6_meses_m_ovi' => 'nullable|integer',
            'total_machos_ovi' => 'nullable|integer',
            'total_ovinos' => 'nullable|integer',
            'men_6_meses_h_capri' => 'nullable|integer',
            'may_6_meses_h_capri' => 'nullable|integer',
            'total_hembras_capri' => 'nullable|integer',
            'men_6_meses_m_capri' => 'nullable|integer',
            'may_6_meses_m_capri' => 'nullable|integer',
            'total_machos_capri' => 'nullable|integer',
            'total_caprinos' => 'nullable|integer',
        ]);

        CensoOvinoCaprino::create($validatedData);

        return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
        ->with('success', 'Censo Ovino Caprino creado exitosamente.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Error al crear el Censo Ovino-Caprino: ' . $e->getMessage()]);
    }
}


public function show($id)
{
    // Obtener el id del predio asociado
    $predioId = Predios::findOrFail($id)->id;
    $censoExistente = CensoOvinoCaprino::where('id_predio', $predioId)->exists();
    $CensoOvinoCaprino = CensoOvinoCaprino::where('id_predio', $predioId)->first(); 
    return view('censo_ovino_caprino.show', compact('predioId', 'CensoOvinoCaprino', 'censoExistente'));
}

public function update(Request $request, $id)
{
    try {
        $validatedData = $request->validate([
            'id_predio' => 'nullable|exists:predios,id',
            'men_6_meses_h_ovi' => 'nullable|integer',
            'may_6_meses_h_ovi' => 'nullable|integer',
            'total_hembras_ovinas' => 'nullable|integer',
            'men_6_meses_m_ovi' => 'nullable|integer',
            'may_6_meses_m_ovi' => 'nullable|integer',
            'total_machos_ovi' => 'nullable|integer',
            'total_ovinos' => 'nullable|integer',
            'men_6_meses_h_capri' => 'nullable|integer',
            'may_6_meses_h_capri' => 'nullable|integer',
            'total_hembras_capri' => 'nullable|integer',
            'men_6_meses_m_capri' => 'nullable|integer',
            'may_6_meses_m_capri' => 'nullable|integer',
            'total_machos_capri' => 'nullable|integer',
            'total_caprinos' => 'nullable|integer',
        ]);

        $censoOvinoCaprino = CensoOvinoCaprino::findOrFail($id);
        $censoOvinoCaprino->update($validatedData);

        return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
            ->with('success', 'Censo Ovino-Caprino actualizado exitosamente.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Error al actualizar el Censo Ovino-Caprino: ' . $e->getMessage()]);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CensoOvinoCaprino $censoOvinoCaprino)
    {
        //
    }
}
