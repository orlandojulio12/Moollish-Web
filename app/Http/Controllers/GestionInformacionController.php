<?php

namespace App\Http\Controllers;

use App\Models\GestionInformacion;
use App\Models\Predios;
use Illuminate\Http\Request;

class GestionInformacionController extends Controller
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
        $predioId = Predios::findOrFail($id)->id;
        $GestionExists = GestionInformacion::where('id_predio', $predioId)->exists();
        $gestionInformacion = $GestionExists ? GestionInformacion::where('id_predio', $predioId)->first() : new GestionInformacion;
        return view('gestion_informacion.create', compact('predioId', 'GestionExists', 'gestionInformacion'));
    }

    public function store(Request $request, $id)
{
    $request->validate([
        'donde_regis_info_finca' => 'required|string',
        'los_registros_son' => 'required|string',
        'calcula_indicadores' => 'required|string',
        'calcula_indicadores_de' => 'nullable|string',
        'calcula_indicadores_de_para' => 'nullable|string',
        'la_informacion_es' => 'required|string',
        'utiliza_software_monitore' => 'required|string',
        'utiliza_software_monitore_cual' => 'nullable|string',
    ]);

    GestionInformacion::create([
        'id_predio' => $id,
        'donde_regis_info_finca' => $request->donde_regis_info_finca,
        'los_registros_son' => $request->los_registros_son,
        'calcula_indicadores' => $request->calcula_indicadores,
        'calcula_indicadores_de' => $request->calcula_indicadores_de,
        'calcula_indicadores_de_para' => $request->calcula_indicadores_de_para,
        'la_informacion_es' => $request->la_informacion_es,
        'utiliza_software_monitore' => $request->utiliza_software_monitore,
        'utiliza_software_monitore_cual' => $request->utiliza_software_monitore_cual,
    ]);

    // Redirigir usando el $id que ya tienes como parámetro
    return redirect()->route('Seccion2', ['id' => $id])
                     ->with('success', 'Caracterización Completada Satisfactoriamente');
}


    public function update(Request $request, $id)
    {
        try {
            // Validar los datos del formulario
            $request->validate([
                'donde_regis_info_finca' => 'required|string',
                'los_registros_son' => 'required|string',
                'calcula_indicadores' => 'required|string',
                'calcula_indicadores_de' => 'nullable|string',
                'calcula_indicadores_de_para' => 'nullable|string',
                'la_informacion_es' => 'required|string',
                'utiliza_software_monitore' => 'required|string',
                'utiliza_software_monitore_cual' => 'nullable|string',
            ]);
    
            // Buscar el registro existente
            $gestionInformacion = GestionInformacion::findOrFail($id);
    
            // Actualizar los datos
            $gestionInformacion->update([
                'donde_regis_info_finca' => $request->donde_regis_info_finca,
                'los_registros_son' => $request->los_registros_son,
                'calcula_indicadores' => $request->calcula_indicadores,
                'calcula_indicadores_de' => $request->calcula_indicadores_de,
                'calcula_indicadores_de_para' => $request->calcula_indicadores_de_para,
                'la_informacion_es' => $request->la_informacion_es,
                'utiliza_software_monitore' => $request->utiliza_software_monitore,
                'utiliza_software_monitore_cual' => $request->utiliza_software_monitore_cual,
            ]);
    
            // Redirigir al usuario después de la actualización
            return redirect()->route('Seccion2', ['id' => $request->id_predio])
            ->with('success', 'Información actualizada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la información: ' . $e->getMessage());
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GestionInformacion $gestionInformacion)
    {
        //
    }
}
