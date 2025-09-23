<?php

namespace App\Http\Controllers;

use App\Models\InformacionBgp;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\InformacionBgpRequest;
use App\Models\Predios;
use App\Models\TiposInformacionBgp;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class InformacionBgpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $informacionBgps = InformacionBgp::paginate();

        return view('informacion-bgp.index', compact('informacionBgps'))
            ->with('i', ($request->input('page', 1) - 1) * $informacionBgps->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id): View
    {
        $informacionBgp = new InformacionBgp();
        $predios = Predios::all();
        $tiposBgp = TiposInformacionBgp::get();  // Asegúrate de tener el modelo TipoBgp

        return view('informacion-bgp.create', compact('informacionBgp', 'predios', 'tiposBgp','id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InformacionBgpRequest $request): RedirectResponse
    {
      
        // Obtener datos validados del request
        $data = $request->validated();
        
        // Si `tipo` y `estado` son arrays, deberías procesar cada elemento
        foreach ($data['estado'] as $index => $tipo) {
            InformacionBgp::create([
                'id_predio' => $data['id_predio'],
                'id_tipos_bgp' => $data['id_tipos_bgp'][$index], // Asegúrate de que estos índices coincidan
                'estado' => $data['estado'][$index], // Similar para `estado`
            ]);
        }
    
        return redirect()->route('Secciones', ['id' => $request->id_predio])
            ->with('success', 'InformacionBgp Creada Satisfactoriamente.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        // Asumiendo que 'id' es el ID del predio, y lo renombramos para claridad
        $id_predio = $id;
    
        // Obtener todos los registros donde 'id_predio' sea igual al valor proporcionado
        $informacionBgp = InformacionBgp::where('id_predio', $id_predio)->get();
        $tiposBgp = TiposInformacionBgp::all();
    
        // Pasar tanto 'informacionBgp' como 'id_predio' a la vista
        return view('informacion-bgp.show', compact('informacionBgp', 'id_predio', 'tiposBgp'));
    }
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $informacionBgp = InformacionBgp::find($id);
        $predios = Predios::all();
        $tiposBgp = TiposInformacionBgp::all();  // Asegúrate de tener el modelo TipoBgp

        return view('informacion-bgp.edit', compact('informacionBgp', 'predios', 'tiposBgp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InformacionBgpRequest $request, InformacionBgp $informacionBgp): RedirectResponse
    {
        $informacionBgp->update($request->validated());

        return Redirect::route('informacion-bgps.index')
            ->with('success', 'InformacionBgp updated successfully');
    }

    public function updateInformacionBgp(InformacionBgpRequest $request, $id): RedirectResponse
{
    // Obtener los datos validados
    $data = $request->validated();

    // Iterar sobre cada tipo y estado para actualizarlos individualmente
    foreach ($data['tipo'] as $index => $tipo) {
        // Encuentra cada registro individual basado en el id y los índices correspondientes
        $informacionBgp = InformacionBgp::where('id_predio', $id)
                                         ->where('id_tipos_bgp', $data['id_tipos_bgp'][$index])
                                         ->first();

        // Asegúrate de que el registro existe antes de intentar actualizarlo
        if ($informacionBgp) {
            $informacionBgp->update([
                'tipo' => $tipo,
                'estado' => $data['estado'][$index],
            ]);
        }
    }

    return redirect()->route('predios.index')->with('success', 'Información BGP actualizada satisfactoriamente.');
}



    public function destroy($id): RedirectResponse
    {
        InformacionBgp::find($id)->delete();

        return Redirect::route('informacion-bgps.index')
            ->with('success', 'InformacionBgp deleted successfully');
    }

}
