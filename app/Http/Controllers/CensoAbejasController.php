<?php

namespace App\Http\Controllers;

use App\Models\CensoAbejas;
use App\Models\Predios;
use App\Models\TipoAbejas;
use App\Models\Municipio;
use Illuminate\Http\Request;

class CensoAbejasController extends Controller
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
                'id_predio' => 'required|exists:predios,id',
                'id_tipo_abejas' => 'required|array',
                'id_tipo_abejas.*' => 'required|exists:tipo_abejas,id',
                'num_apiarios' => 'required|array',
                'num_apiarios.*' => 'required|integer|min:0',
                'num_colmenas' => 'required|array',
                'num_colmenas.*' => 'required|integer|min:0',
                'poblacion_estimada' => 'nullable|array',
                'poblacion_estimada.*' => 'nullable|string|max:255',
                'realiz_trashumancia' => 'nullable|array',
                'realiz_trashumancia.*' => 'nullable|string|max:255',
                'nom_estable_destino' => 'nullable|array',
                'nom_estable_destino.*' => 'nullable|string|max:255',
                'departamento' => 'required|array',
                'departamento.*' => 'required|string|max:255',
                'municipio' => 'required|array',
                'municipio.*' => 'required|string|max:255',
            ]);
    
            // Debug para verificar los datos recibidos
            
    
            foreach ($validatedData['id_tipo_abejas'] as $index => $tipoAbejaId) {
                CensoAbejas::create([
                    'id_predio' => $validatedData['id_predio'],
                    'id_tipo_abejas' => $tipoAbejaId,
                    'num_apiarios' => $validatedData['num_apiarios'][$index],
                    'num_colmenas' => $validatedData['num_colmenas'][$index],
                    'poblacion_estimada' => $validatedData['poblacion_estimada'][$index] ?? null,
                    'realiz_trashumancia' => $validatedData['realiz_trashumancia'][$index] ?? null,
                    'nom_estable_destino' => $validatedData['nom_estable_destino'][$index] ?? null,
                    'departamento' => $validatedData['departamento'][$index],
                    'municipio' => $validatedData['municipio'][$index],
                ]);
            }
    
            return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])->with('success', 'Censo de Abejas creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al crear el Censo de Abejas: ' . $e->getMessage()]);
        }
    }
    

    public function show($id)
{
    $predioId = Predios::findOrFail($id)->id;
    $TipoAbejas = TipoAbejas::all();
    $censoExistente = CensoAbejas::where('id_predio', $predioId)->exists();
    $CensoAbejas = CensoAbejas::where('id_predio', $predioId)->get();

    return view('censo_abejas.show', compact('predioId', 'TipoAbejas', 'CensoAbejas', 'censoExistente'));
}

    public function getMunicipios($departamentoId)
    {
        $municipios = Municipio::where('departamento', $departamentoId)->get();
        return response()->json($municipios);
    }

    public function update(Request $request, $id)
    {
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'id_predio' => 'required|exists:predios,id',
                'id_tipo_abejas' => 'required|array',
                'id_tipo_abejas.*' => 'required|exists:tipo_abejas,id',
                'num_apiarios' => 'required|array',
                'num_apiarios.*' => 'required|integer|min:0',
                'num_colmenas' => 'required|array',
                'num_colmenas.*' => 'required|integer|min:0',
                'poblacion_estimada' => 'nullable|array',
                'poblacion_estimada.*' => 'nullable|string|max:255',
                'realiz_trashumancia' => 'nullable|array',
                'realiz_trashumancia.*' => 'nullable|string|max:255',
                'nom_estable_destino' => 'nullable|array',
                'nom_estable_destino.*' => 'nullable|string|max:255',
                'departamento' => 'required|array',
                'departamento.*' => 'required|string|max:255',
                'municipio' => 'required|array',
                'municipio.*' => 'required|string|max:255',
            ]);
    
            // Obtener los registros existentes de CensoAbejas para el predio
            $censosExistentes = CensoAbejas::where('id_predio', $validatedData['id_predio'])->get();
    
            // Actualizar los registros existentes
            foreach ($censosExistentes as $index => $censo) {
                $censo->update([
                    'id_tipo_abejas' => $validatedData['id_tipo_abejas'][$index],
                    'num_apiarios' => $validatedData['num_apiarios'][$index],
                    'num_colmenas' => $validatedData['num_colmenas'][$index],
                    'poblacion_estimada' => $validatedData['poblacion_estimada'][$index] ?? null,
                    'realiz_trashumancia' => $validatedData['realiz_trashumancia'][$index] ?? null,
                    'nom_estable_destino' => $validatedData['nom_estable_destino'][$index] ?? null,
                    'departamento' => $validatedData['departamento'][$index],
                    'municipio' => $validatedData['municipio'][$index],
                ]);
            }
    
            return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])->with('success', 'Censo de Abejas actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al actualizar el Censo de Abejas: ' . $e->getMessage()]);
        }
    }
    
    public function destroy(CensoAbejas $censoAbejas)
    {
        //
    }
}
