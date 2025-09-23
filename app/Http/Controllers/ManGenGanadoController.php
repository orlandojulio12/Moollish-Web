<?php

namespace App\Http\Controllers;

use App\Models\ManGenGanado;
use App\Models\RazasGanado;
use App\Models\Predios;
use Illuminate\Http\Request;

class ManGenGanadoController extends Controller
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
    // Mostrar el formulario de creación
    public function create($id)
    {
        $razas=RazasGanado::all();
        $predioId = Predios::findOrFail($id)->id;
        $ManExists = ManGenGanado::where('id_predio', $predioId)->exists();
        $manGenGanado = $ManExists ? ManGenGanado::where('id_predio', $predioId)->first() : new ManGenGanado;
        return view('man_gen_ganado.create', compact('predioId','razas', 'ManExists', 'manGenGanado'));
    }

    // Guardar los datos del formulario
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'id_predio' => 'required|exists:predios,id',
            'id_raza_gan' => 'nullable|integer',
            'ident_animales' => 'nullable|string',
            'sistema_cria_ternero' => 'nullable|string',
            'aliment_ternero' => 'nullable|string',
            'sistem_levant_animal' => 'nullable|string',
            'manej_hembras_prox' => 'nullable|string',
            'manej_vacas_secas' => 'nullable|string',
            'tipo_ordeño' => 'nullable|string',
            'sistem_servic_reproduct' => 'nullable|string',
            'form_program_servicios' => 'nullable|string',
            'pesaje_animal' => 'nullable|string',
            'cuantos_animal_pesa' => 'nullable|string',
            'control_parasito_extern' => 'nullable|string',
            'control_parasito_extern_produc' => 'nullable|string',
            'control_parasito_extern_frecuenc' => 'nullable|string',
            'control_parasito_intern' => 'nullable|string',
            'control_parasito_intern_produc' => 'nullable|string',
            'control_parasito_intern_frecuenc' => 'nullable|string',
            'sumin_sal' => 'nullable|string',
            'a_sal_add_premezcla' => 'nullable|string',
            'a_sal_add_premezcla_especifique' => 'nullable|string',
            'como_manej_ganad_veran' => 'nullable|string',
            'como_manej_ganad_invier' => 'nullable|string',
            'r_pesaje_leche_hembr_lactantes' => 'nullable|string',
            'r_pesaje_leche_hembr_periodicidad' => 'nullable|string',
            'suplement_ganad_epoc_criti' => 'nullable|string',
            'suplement_ganad_epoc_criti_con_que' => 'nullable|string',
            'suplement_ganad_epoc_criti_que_lotes' => 'nullable|string',
        ]);

        // Crear un nuevo registro en la base de datos
        ManGenGanado::create($request->all());

        // Redirigir al formulario anterior o a la página deseada
        return redirect()->route('Seccion2', ['id' => $request->id_predio])
                         ->with('success', 'Información general del ganado guardada correctamente');
    }

    public function update(Request $request, $id)
{
    // Validar los datos del formulario
    $request->validate([
        'id_predio' => 'required|exists:predios,id',
        'id_raza_gan' => 'nullable|integer',
        'ident_animales' => 'nullable|string',
        'sistema_cria_ternero' => 'nullable|string',
        'aliment_ternero' => 'nullable|string',
        'sistem_levant_animal' => 'nullable|string',
        'manej_hembras_prox' => 'nullable|string',
        'manej_vacas_secas' => 'nullable|string',
        'tipo_ordeño' => 'nullable|string',
        'sistem_servic_reproduct' => 'nullable|string',
        'form_program_servicios' => 'nullable|string',
        'pesaje_animal' => 'nullable|string',
        'cuantos_animal_pesa' => 'nullable|string',
        'control_parasito_extern' => 'nullable|string',
        'control_parasito_extern_produc' => 'nullable|string',
        'control_parasito_extern_frecuenc' => 'nullable|string',
        'control_parasito_intern' => 'nullable|string',
        'control_parasito_intern_produc' => 'nullable|string',
        'control_parasito_intern_frecuenc' => 'nullable|string',
        'sumin_sal' => 'nullable|string',
        'a_sal_add_premezcla' => 'nullable|string',
        'a_sal_add_premezcla_especifique' => 'nullable|string',
        'como_manej_ganad_veran' => 'nullable|string',
        'como_manej_ganad_invier' => 'nullable|string',
        'r_pesaje_leche_hembr_lactantes' => 'nullable|string',
        'r_pesaje_leche_hembr_periodicidad' => 'nullable|string',
        'suplement_ganad_epoc_criti' => 'nullable|string',
        'suplement_ganad_epoc_criti_con_que' => 'nullable|string',
        'suplement_ganad_epoc_criti_que_lotes' => 'nullable|string',
    ]);

    // Buscar el registro existente
    $manGenGanado = ManGenGanado::findOrFail($id);

    // Actualizar los datos
    $manGenGanado->update($request->all());

    // Redirigir a la página deseada
    return redirect()->route('Seccion2', ['id' => $request->id_predio])
                     ->with('success', 'Información general del ganado actualizada correctamente');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManGenGanado $manGenGanado)
    {
        //
    }
}
