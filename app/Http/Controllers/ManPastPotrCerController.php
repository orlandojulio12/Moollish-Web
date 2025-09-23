<?php

namespace App\Http\Controllers;

use App\Models\ManPastPotrCer;
use App\Models\Predios;
use Illuminate\Http\Request;

class ManPastPotrCerController extends Controller
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
    $ManExists = ManPastPotrCer::where('id_predio', $predioId)->exists();
    
    $ManPastPotrCer = $ManExists ? ManPastPotrCer::where('id_predio', $predioId)->first() : new ManPastPotrCer;


        return view('man_past_potr_cerc.create', compact('predioId', 'ManExists', 'ManPastPotrCer'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_predio' => 'required|exists:predios,id',
            'area_dest_past' => 'nullable|string',
            'r_fertilazion_potreros' => 'nullable|string',
            'r_fertilazion_potreros_produc' => 'nullable|string',
            'r_fertilazion_potreros_cuant_año' => 'nullable|integer',
            'presen_plag_enferm' => 'nullable|string',
            'presen_plag_enferm_tipos' => 'nullable|string',
            'r_control_plagas' => 'nullable|string',
            'r_control_plagas_produc' => 'nullable|string',
            'r_control_plagas_cuant_año' => 'nullable|integer',
            'r_control_maleza' => 'nullable|string',
            'r_control_maleza_product' => 'nullable|string',
            'r_control_maleza_cuant_año' => 'nullable|integer',
            'precencia_heladas' => 'nullable|string',
            'precencia_heladas_intensidad' => 'nullable|string',
            'precencia_heladas_epocas' => 'nullable|string',
            'div_potreros' => 'nullable|string',
            'div_potreros_como' => 'nullable|string',
            'tipo_pastoreo' => 'nullable|string',
            'rotacional_dias_ocupacion' => 'nullable|string ',
            'rotacional_dias_descanso' => 'nullable|string',
            'cercas' => 'nullable|string',
            'cercas_puas' => 'nullable|string',
            'cercas_electricas' => 'nullable|string',
            'la_produccion_forraje_suficiente_año' => 'nullable|string',
            'porque' => 'nullable|string',
        ]);

        ManPastPotrCer::create($validatedData);

        return redirect()->route('Seccion2', ['id' => $request->id_predio])
        ->with('success', 'Información del predio guardada correctamente');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id_predio' => 'required|exists:predios,id',
            'area_dest_past' => 'nullable|string',
            'r_fertilazion_potreros' => 'nullable|string',
            'r_fertilazion_potreros_produc' => 'nullable|string',
            'r_fertilazion_potreros_cuant_año' => 'nullable|integer',
            'presen_plag_enferm' => 'nullable|string',
            'presen_plag_enferm_tipos' => 'nullable|string',
            'r_control_plagas' => 'nullable|string',
            'r_control_plagas_produc' => 'nullable|string',
            'r_control_plagas_cuant_año' => 'nullable|integer',
            'r_control_maleza' => 'nullable|string',
            'r_control_maleza_product' => 'nullable|string',
            'r_control_maleza_cuant_año' => 'nullable|integer',
            'precencia_heladas' => 'nullable|string',
            'precencia_heladas_intensidad' => 'nullable|string',
            'precencia_heladas_epocas' => 'nullable|string',
            'div_potreros' => 'nullable|string',
            'div_potreros_como' => 'nullable|string',
            'tipo_pastoreo' => 'nullable|string',
            'rotacional_dias_ocupacion' => 'nullable|string',
            'rotacional_dias_descanso' => 'nullable|string',
            'cercas' => 'nullable|string',
            'cercas_puas' => 'nullable|string',
            'cercas_electricas' => 'nullable|string',
            'la_produccion_forraje_suficiente_año' => 'nullable|string',
            'porque' => 'nullable|string',
        ]);
    
        // Encuentra el registro por su ID y actualiza los datos
        $manPastPotrCer = ManPastPotrCer::findOrFail($id);
        $manPastPotrCer->update($validatedData);
    
        return redirect()->route('man_gen_ganado.create', ['id_predio' => $request->id_predio])
            ->with('success', 'Información del predio actualizada correctamente');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManPastPotrCer $manPastPotrCer)
    {
        //
    }
}
