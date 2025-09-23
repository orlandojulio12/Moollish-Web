<?php

namespace App\Http\Controllers;

use App\Models\CarazterizacionRiesgo;
use App\Models\Predios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CarazterizacionRiesgoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
  

    public function Datos()
    {
        // 1. Proporción de predios que colindan con establecimientos de riesgo
        $colindaEstablecim = DB::table('caracterizacion_riesgo')
            ->selectRaw("
                IFNULL(COUNT(CASE WHEN colinda_establecim_riesgo = 'Si' THEN 1 END), 0) AS colinda,
                IFNULL(COUNT(CASE WHEN colinda_establecim_riesgo = 'No' THEN 1 END), 0) AS no_colinda
            ")
            ->first();
    
        // 2. Frecuencia de ubicación en vía
        $ubicacionVia = DB::table('caracterizacion_riesgo')
            ->select('ubica_en_via', DB::raw('COUNT(*) AS frecuencia'))
            ->groupBy('ubica_en_via')
            ->get();
    
        // 3. Frecuencia de tipos de alimentación de animales
        $alimenAnimal = DB::table('caracterizacion_riesgo')
            ->select('alimen_animal', DB::raw('COUNT(*) AS frecuencia'))
            ->groupBy('alimen_animal')
            ->get();
    
        // 4. Proporción de predios que suministran desperdicios alimentarios para porcinos
        $lavazasPorcinos = DB::table('caracterizacion_riesgo')
            ->selectRaw("
                IFNULL(COUNT(CASE WHEN lavazas_desper_alimen_porc = 'Si' THEN 1 END), 0) AS suministra,
                IFNULL(COUNT(CASE WHEN lavazas_desper_alimen_porc = 'No' THEN 1 END), 0) AS no_suministra
            ")
            ->first();
    
        // 5. Proporción de predios donde se realiza sacrificio de animales
        $sacrificioAnimales = DB::table('caracterizacion_riesgo')
            ->selectRaw("
                IFNULL(COUNT(CASE WHEN sacrif_anim_pred = 'Si' THEN 1 END), 0) AS realiza_sacrificio,
                IFNULL(COUNT(CASE WHEN sacrif_anim_pred = 'No' THEN 1 END), 0) AS no_realiza_sacrificio
            ")
            ->first();
    
        // 6. Proporción de predios que reciben asistencia técnica
        $asistenciaTecnica = DB::table('caracterizacion_riesgo')
            ->selectRaw("
                IFNULL(COUNT(CASE WHEN asistencia_tecnica = 'Si' THEN 1 END), 0) AS recibe_asistencia,
                IFNULL(COUNT(CASE WHEN asistencia_tecnica = 'No' THEN 1 END), 0) AS no_recibe_asistencia
            ")
            ->first();
    
        // 7. Distribución del número de trabajadores en los predios
        $numTrabajadores = DB::table('caracterizacion_riesgo')
            ->select('num_trabajadores', DB::raw('COUNT(*) AS frecuencia'))
            ->groupBy('num_trabajadores')
            ->orderBy('num_trabajadores')
            ->get();
    
        // Pasar todos los datos a la vista
        return view('caracterizacion_riesgo.datos', compact(
            'colindaEstablecim', 
            'ubicacionVia', 
            'alimenAnimal', 
            'lavazasPorcinos', 
            'sacrificioAnimales', 
            'asistenciaTecnica', 
            'numTrabajadores'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $predioId = Predios::findOrFail($id)->id;
        $RiesgoExists = CarazterizacionRiesgo::where('id_predio', $predioId)->exists();
        $caracterizacionRiesgo = $RiesgoExists ? CarazterizacionRiesgo::where('id_predio', $predioId)->first() : new CarazterizacionRiesgo;
        return view('caracterizacion_riesgo.create', compact('predioId', 'RiesgoExists', 'caracterizacionRiesgo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'id_predio' => 'required|integer|exists:predios,id',
            'colinda_establecim_riesgo' => 'required|in:Si,No',
            'colinda_establecim_cual' => 'nullable|string',
            'ubica_en_via' => 'required|string',
            'alimen_animal' => 'required|string',
            'alimen_animl_otro' => 'nullable|string',
            'lavazas_desper_alimen_porc' => 'required|in:Si,No',
            'real_coccion_previa' => 'required|in:Si,No',
            'sacrif_anim_pred' => 'required|in:Si,No',
            'sacrif_anim_pred_periodic' => 'nullable|string',
            'servic_reproduc' => 'required|string',
            'servic_reproduc_otro' => 'nullable|string',
            'num_trabajadores' => 'required|integer',
            'trabajan_otr_explotacion' => 'required|in:Si,No',
            'asistencia_tecnica' => 'required|in:Si,No',
            'asistencia_tecnica_frecuen' => 'nullable|string',
            'atiend_otr_predi' => 'required|in:Si,No',
            'atiend_otr_predi_cual' => 'nullable|string'
        ]);

        // Crear un nuevo registro en la base de datos
        CarazterizacionRiesgo::create($validatedData);

        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('Seccion4', ['id' => $request->id_predio])
        ->with('success', 'Caracterización de riesgo guardada exitosamente.');
    }

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'id_predio' => 'required|integer|exists:predios,id',
            'colinda_establecim_riesgo' => 'required|in:Si,No',
            'colinda_establecim_cual' => 'nullable|string',
            'ubica_en_via' => 'required|string',
            'alimen_animal' => 'required|string',
            'alimen_animl_otro' => 'nullable|string',
            'lavazas_desper_alimen_porc' => 'required|in:Si,No',
            'real_coccion_previa' => 'required|in:Si,No',
            'sacrif_anim_pred' => 'required|in:Si,No',
            'sacrif_anim_pred_periodic' => 'nullable|string',
            'servic_reproduc' => 'required|string',
            'servic_reproduc_otro' => 'nullable|string',
            'num_trabajadores' => 'required|integer',
            'trabajan_otr_explotacion' => 'required|in:Si,No',
            'asistencia_tecnica' => 'required|in:Si,No',
            'asistencia_tecnica_frecuen' => 'nullable|string',
            'atiend_otr_predi' => 'required|in:Si,No',
            'atiend_otr_predi_cual' => 'nullable|string'
        ]);
    
        // Buscar el registro existente
        $caracterizacionRiesgo = CarazterizacionRiesgo::findOrFail($id);
    
        // Actualizar los datos
        $caracterizacionRiesgo->update($validatedData);
    
        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('Seccion4', ['id' => $request->id_predio])
                         ->with('success', 'Caracterización de riesgo actualizada exitosamente.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarazterizacionRiesgo $carazterizacionRiesgo)
    {
        //
    }
}
