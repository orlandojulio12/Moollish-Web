<?php

namespace App\Http\Controllers;

use App\Models\InfoTierraAgua;
use App\Models\Predios;
use Illuminate\Http\Request;

class InfoTierraAguaController extends Controller
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
    $InfoExists = InfoTierraAgua::where('id_predio', $predioId)->exists();

    // Si la información existe, busca los datos, de lo contrario, inicializa una nueva instancia
    $infoTierraAgua = $InfoExists ? InfoTierraAgua::where('id_predio', $predioId)->first() : new InfoTierraAgua;

    return view('info_tierra_agua.create', compact('predioId', 'InfoExists', 'infoTierraAgua'));
}


    public function store(Request $request)
    {
        $request->validate([
            'id_predio' => 'required|exists:predios,id',
            'suelos_predominantes' => 'nullable|string',
            'drenaje' => 'nullable|string',
            'manejo_cuencas_nac_agua' => 'nullable|string',
            'cantidad_preservacion' => 'nullable|integer',
            'porcentaje_preservacion' => 'nullable|integer',
            'fuente_calidad_agua' => 'nullable|string',
            'fuente_calidad_agua_uso_domestic' => 'nullable|string',
            'disp_agua_durant_veran_anim' => 'nullable|string',
            'disp_agua_durant_veran_anim_fuente' => 'nullable|string',
            'disp_agua_durant_veran_riesg' => 'nullable|string',
            'disp_agua_durant_veran_riesg_fuente' => 'nullable|string',
        ]);

        InfoTierraAgua::create($request->all());

        return redirect()->route('Seccion2', ['id' => $request->id_predio])
                 ->with('success', 'Información del predio guardada correctamente');
    }

    public function show(InfoTierraAgua $infoTierraAgua)
    {
        //
    }

    public function updatadeCaracterizacion(Request $request, $id)
{
    $request->validate([
        'suelos_predominantes' => 'nullable|string',
        'drenaje' => 'nullable|string',
        'manejo_cuencas_nac_agua' => 'nullable|string',
        'cantidad_preservacion' => 'nullable|integer',
        'porcentaje_preservacion' => 'nullable|integer',
        'fuente_calidad_agua' => 'nullable|string',
        'fuente_calidad_agua_uso_domestic' => 'nullable|string',
        'disp_agua_durant_veran_anim' => 'nullable|string',
        'disp_agua_durant_veran_anim_fuente' => 'nullable|string',
        'disp_agua_durant_veran_riesg' => 'nullable|string',
        'disp_agua_durant_veran_riesg_fuente' => 'nullable|string',
    ]);

    $infoTierraAgua = InfoTierraAgua::findOrFail($id);
    $infoTierraAgua->update($request->all());

    return redirect()->route('Seccion2', ['id' => $request->id_predio])
                     ->with('success', 'Información actualizada correctamente');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InfoTierraAgua $infoTierraAgua)
    {
        //
    }
}
