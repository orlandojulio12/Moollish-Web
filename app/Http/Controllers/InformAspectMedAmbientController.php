<?php

namespace App\Http\Controllers;

use App\Models\InformAspectMedAmbient;
use App\Models\Predios;
use Illuminate\Http\Request;

class InformAspectMedAmbientController extends Controller
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
    public function create($id)
    {
               // Obtener el id del predio
    $predioId = Predios::findOrFail($id)->id;

    // Verificar si ya existe información de tierra y agua para el predio
    $InfoExists = InformAspectMedAmbient::where('id_predio', $predioId)->exists();
    
    $informAspectMedAmbient = $InfoExists ? InformAspectMedAmbient::where('id_predio', $predioId)->first() : new InformAspectMedAmbient;

        return view('info_aspect_med_ambient.create', compact('predioId', 'informAspectMedAmbient', 'InfoExists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_predio' => 'required|exists:predios,id',
            'dispos_aguas_servid' => 'nullable|string',
            'dispos_excrement_bovinos' => 'nullable|string',
            'manejo_basuras' => 'nullable|string',
            'manejo_empaq_produc_quimic' => 'nullable|string',
        ]);

        InformAspectMedAmbient::create($request->all());

        return redirect()->route('Seccion2', ['id' => $request->id_predio])
                         ->with('success', 'Información guardada correctamente');
    }

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'id_predio' => 'required|exists:predios,id',
            'dispos_aguas_servid' => 'nullable|string',
            'dispos_excrement_bovinos' => 'nullable|string',
            'manejo_basuras' => 'nullable|string',
            'manejo_empaq_produc_quimic' => 'nullable|string',
        ]);
    
        // Buscar el registro existente
        $informAspectMedAmbient = InformAspectMedAmbient::findOrFail($id);
    
        // Actualizar los datos
        $informAspectMedAmbient->update($request->all());
    
        // Redirigir a la página deseada
        return redirect()->route('Seccion2', ['id' => $request->id_predio])
                         ->with('success', 'Información actualizada correctamente');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InformAspectMedAmbient $informAspectMedAmbient)
    {
        //
    }
}
