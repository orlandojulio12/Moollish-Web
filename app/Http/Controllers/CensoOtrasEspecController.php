<?php

namespace App\Http\Controllers;

use App\Models\CensoOtrasEspec;
use App\Models\Predios;
use Illuminate\Http\Request;

class CensoOtrasEspecController extends Controller
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
            'llamas' => 'nullable|integer',
            'alpacas' => 'nullable|integer',
            'avectruces' => 'nullable|integer',
            'otras' => 'nullable|string|max:255',
            'cuantas_otras' => 'nullable|integer',
        ]);

        CensoOtrasEspec::create($validatedData);

        return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
        ->with('success', 'Censo Ovino Caprino creado exitosamente.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Error al crear el Censo Otras Especies: ' . $e->getMessage()]);
    }
}

public function show($id)
{
    // Obtener el id del predio asociado
    $predioId = Predios::findOrFail($id)->id;
    
    $censoExistente = CensoOtrasEspec::where('id_predio', $predioId)->exists();

    $CensoOtrasEspec = CensoOtrasEspec::where('id_predio', $predioId)->first();
    
    return view('censo_otras_espec.show', compact('predioId', 'CensoOtrasEspec', 'censoExistente'));
}

public function update(Request $request, $id)
{
    try {
        $validatedData = $request->validate([
            'id_predio' => 'nullable|exists:predios,id',
            'llamas' => 'nullable|integer',
            'alpacas' => 'nullable|integer',
            'avectruces' => 'nullable|integer',
            'otras' => 'nullable|string|max:255',
            'cuantas_otras' => 'nullable|integer',
        ]);

        $censoOtrasEspec = CensoOtrasEspec::findOrFail($id);
        $censoOtrasEspec->update($validatedData);

        return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
            ->with('success', 'Censo Otras Especies actualizado exitosamente.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Error al actualizar el Censo Otras Especies: ' . $e->getMessage()]);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CensoOtrasEspec $censoOtrasEspec)
    {
        //
    }
}
