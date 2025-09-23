<?php

namespace App\Http\Controllers;

use App\Models\InforEpidemiologica;
use App\Models\Predios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class InforEpidemiologicaController extends Controller
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
       // Primera consulta: Proporción de predios con enfermedades
    $resultEnfermedades = DB::table('infor_epidemiologica')
    ->selectRaw("
        IFNULL(COUNT(CASE WHEN anim_enferm_control = 'Si' THEN 1 END), 0) AS predios_con_enfermedad,
        IFNULL(COUNT(CASE WHEN anim_enferm_control = 'No' THEN 1 END), 0) AS predios_sin_enfermedad
    ")
    ->first();

// Segunda consulta: Proporción de predios con toma de muestra
$resultMuestras = DB::table('infor_epidemiologica')
    ->selectRaw("
        IFNULL(COUNT(CASE WHEN toma_muestra = 'Si' THEN 1 END), 0) AS predios_con_muestra,
        IFNULL(COUNT(CASE WHEN toma_muestra = 'No' THEN 1 END), 0) AS predios_sin_muestra
    ")
    ->first();

// Tercera consulta: Tipos de muestras más comunes
$tiposMuestras = DB::table('infor_epidemiologica')
    ->select('toma_muestra_tipos', DB::raw('COUNT(*) AS frecuencia'))
    ->where('toma_muestra', 'Si')
    ->groupBy('toma_muestra_tipos')
    ->orderByDesc('frecuencia')
    ->limit(5)
    ->get();
    
        // Pasar ambos resultados a la vista
        return view('InforEpidemiologica.datos', compact('resultEnfermedades', 'resultMuestras', 'tiposMuestras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $predioId = Predios::findOrFail($id)->id;
        $InfoExists = InforEpidemiologica::where('id_predio', $predioId)->exists();
        $inforEpidemiologica = $InfoExists ? InforEpidemiologica::where('id_predio', $predioId)->first() : new InforEpidemiologica;
        return view('InforEpidemiologica.create', compact('predioId', 'InfoExists', 'inforEpidemiologica'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'id_predio' => 'nullable|exists:predios,id',
            'anim_enferm_control' => 'nullable|string|max:255',
            'anim_enferm_control_cant' => 'nullable|string|max:255',
            'cuadr_clinc_sospec' => 'nullable|string|max:255',
            'especies_afectadas' => 'nullable|string|max:255',
            'toma_muestra' => 'nullable|string|max:255',
            'toma_muestra_tipos' => 'nullable|string|max:255',
            'toma_muestra_numeros' => 'nullable|string|max:255'
        ]);

        InforEpidemiologica::create($request->all());

        return redirect()->route('Seccion4', ['id' => $request->id_predio])
        ->with('success', 'Información epidemiológica creada correctamente.');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'id_predio' => 'nullable|exists:predios,id',
        'anim_enferm_control' => 'nullable|string|max:255',
        'anim_enferm_control_cant' => 'nullable|string|max:255',
        'cuadr_clinc_sospec' => 'nullable|string|max:255',
        'especies_afectadas' => 'nullable|string|max:255',
        'toma_muestra' => 'nullable|string|max:255',
        'toma_muestra_tipos' => 'nullable|string|max:255',
        'toma_muestra_numeros' => 'nullable|string|max:255',
    ]);

    // Buscar el registro existente
    $inforEpidemiologica = InforEpidemiologica::findOrFail($id);

    // Actualizar los datos
    $inforEpidemiologica->update($request->all());

    // Redirigir a la página deseada
    return redirect()->route('Seccion4', ['id' => $request->id_predio])
                     ->with('success', 'Información epidemiológica actualizada correctamente.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InforEpidemiologica $InforEpidemiologica)
    {
        //
    }
}
