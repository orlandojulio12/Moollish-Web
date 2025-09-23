<?php

namespace App\Http\Controllers;

use App\Models\ServiciosAmbientales;
use App\Models\TpServicioAmbientales;
use App\Models\Predios;
use Illuminate\Http\Request;

class ServiciosAmbientalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create($id)
    {
        $id_predio = Predios::findOrFail($id)->id;
        $tipos=TpServicioAmbientales::all();
        $ServiciosAmbientalesExists = ServiciosAmbientales::where('id_predio', $id_predio)->exists();
        $servicios_ambientales = ServiciosAmbientales::where('id_predio', $id_predio)->get()->keyBy('id_tip_servicio');
        return view('servicios_ambientales.create', compact('id_predio','tipos', 'ServiciosAmbientalesExists', 'servicios_ambientales'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar datos generales
        $request->validate([
            'id_predio' => 'required|integer|exists:predios,id',
            'hectareas' => 'required|array',
            'hectareas.*' => 'required|numeric|min:0',
            'materiales_establecidos' => 'array',
            'materiales_establecidos.*' => 'nullable|string|max:255',
        ]);

        // Procesar cada tipo de servicio
        foreach ($request->input('hectareas') as $tipo_id => $hectareas) {
            if ($hectareas > 0) { // Asegura que solo se guarden registros con hectáreas mayores a cero
                ServiciosAmbientales::create([
                    'id_predio' => $request->id_predio,
                    'id_tip_servicio' => $tipo_id,
                    'hectareas' => $hectareas,
                    'materiales_establecidos' => $request->materiales_establecidos[$tipo_id] ?? null,
                    'sum_total' => array_sum($request->hectareas) // Calcula el total de todas las hectáreas
                ]);
            }
        }

        // Redireccionar con mensaje de éxito SERVICIOS AMBIENTALES
        return redirect()->route('Secciones', ['id' => $request->id_predio])->with('success', 'Servicios Ambientales Guardada Exitosamente!!');
    }

    public function update(Request $request, $id_predio)
{
    // Validar datos generales
    $request->validate([
        'id_predio' => 'required|integer|exists:predios,id',
        'hectareas' => 'required|array',
        'hectareas.*' => 'required|numeric|min:0',
        'materiales_establecidos' => 'array',
        'materiales_establecidos.*' => 'nullable|string|max:255',
    ]);

    // Procesar cada tipo de servicio
    foreach ($request->input('hectareas') as $tipo_id => $hectareas) {
        if ($hectareas > 0) { // Asegura que solo se guarden registros con hectáreas mayores a cero
            $servicio = ServiciosAmbientales::where('id_predio', $id_predio)
                          ->where('id_tip_servicio', $tipo_id)
                          ->first();

            if ($servicio) {
                // Si ya existe el servicio, lo actualizamos
                $servicio->update([
                    'hectareas' => $hectareas,
                    'materiales_establecidos' => $request->materiales_establecidos[$tipo_id] ?? null,
                    'sum_total' => array_sum($request->hectareas), // Calcula el total de todas las hectáreas
                ]);
            } else {
                // Si no existe, creamos uno nuevo
                ServiciosAmbientales::create([
                    'id_predio' => $request->id_predio,
                    'id_tip_servicio' => $tipo_id,
                    'hectareas' => $hectareas,
                    'materiales_establecidos' => $request->materiales_establecidos[$tipo_id] ?? null,
                    'sum_total' => array_sum($request->hectareas), // Calcula el total de todas las hectáreas
                ]);
            }
        }
    }

    // Redireccionar con mensaje de éxito
    return redirect()->route('Secciones', ['id' => $request->id_predio])->with('success', 'Servicios Ambientales Actualizada Exitosamente!!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiciosAmbientales $serviciosAmbientales)
    {
        //
    }
}
