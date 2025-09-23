<?php

namespace App\Http\Controllers;

use App\Models\Tipo_explotacion;
use App\Models\Predios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoExplotacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    
    public function Datos(Tipo_explotacion $tipo_explotacion)
{
    // Consulta de conteos por tipo de explotación para cada categoría
    $data = [
        'bovinos' => DB::table('tp_explotacion')->select('bovinos as tipo', DB::raw('count(*) as total'))->groupBy('bovinos')->get(),
        'bufalinos' => DB::table('tp_explotacion')->select('bufalinos as tipo', DB::raw('count(*) as total'))->groupBy('bufalinos')->get(),
        'porcinos' => DB::table('tp_explotacion')->select('porcinos as tipo', DB::raw('count(*) as total'))->groupBy('porcinos')->get(),
        'equinos' => DB::table('tp_explotacion')->select('equinos as tipo', DB::raw('count(*) as total'))->groupBy('equinos')->get(),
        'ovinos' => DB::table('tp_explotacion')->select('ovinos as tipo', DB::raw('count(*) as total'))->groupBy('ovinos')->get(),
        'caprinos' => DB::table('tp_explotacion')->select('caprinos as tipo', DB::raw('count(*) as total'))->groupBy('caprinos')->get(),
        'aves_corral' => DB::table('tp_explotacion')->select('aves_corral as tipo', DB::raw('count(*) as total'))->groupBy('aves_corral')->get(),
        'aves_no_corral' => DB::table('tp_explotacion')->select('aves_no_corral as tipo', DB::raw('count(*) as total'))->groupBy('aves_no_corral')->get(),
        'peces' => DB::table('tp_explotacion')->select('peces as tipo', DB::raw('count(*) as total'))->groupBy('peces')->get(),
        'crustaceos' => DB::table('tp_explotacion')->select('crustaceos as tipo', DB::raw('count(*) as total'))->groupBy('crustaceos')->get(),
        'sistem_acuaticos' => DB::table('tp_explotacion')->select('sistem_acuaticos as tipo', DB::raw('count(*) as total'))->groupBy('sistem_acuaticos')->get(),
        'apicolas' => DB::table('tp_explotacion')->select('apicolas as tipo', DB::raw('count(*) as total'))->groupBy('apicolas')->get(),
    ];

    // Indicador de los tipos más comunes en cada categoría
    $summary = [];
    $categoryImages = [
        'bovinos' => 'bocinos.png',
        'bufalinos' => 'bufalinos.png',
        'porcinos' => 'porcinos.png',
        'equinos' => 'equidos.png',
        'ovinos' => 'ovinos y caprios.png',
        'caprinos' => 'ovinos y caprios.png',
        'aves_corral' => 'aves comerciales .png',
        'aves_no_corral' => 'otras aves.png',
        'peces' => 'peces.png',
        'crustaceos' => 'crustaceos.png',
        'sistem_acuaticos' => 'otras especies.png',
        'apicolas' => 'abejas.png'
    ];
    foreach ($data as $key => $values) {
        $total = $values->sum('total');
        $common = $values->sortByDesc('total')->first();
        $summary[$key] = [
            'total' => $total,
            'most_common' => $common->tipo ?? 'N/A',
            'common_count' => $common->total ?? 0,
        ];
    }

    return view('tipo_explotacion.datos', [
        'data' => $data,
        'summary' => $summary,
        'categoryImages' => $categoryImages
    ]);
}

     public function create($id)
    {
        $predioId = Predios::findOrFail($id)->id;
        $TiposExists = Tipo_explotacion::where('id_predio', $predioId)->exists();
        $explotacion = $TiposExists ? Tipo_explotacion::where('id_predio', $predioId)->first() : new Tipo_explotacion;
        return view('tipo_explotacion.create', compact('predioId', 'TiposExists', 'explotacion'));
    }

 
    public function store(Request $request)
    {
        $request->validate([
            'id_predio' => 'required|exists:predios,id',
            'bovinos' => 'nullable|string',
            'bufalinos' => 'nullable|string',
            'porcinos' => 'nullable|string',
            'equinos' => 'nullable|string',
            'ovinos' => 'nullable|string',
            'caprinos' => 'nullable|string',
            'aves_corral' => 'nullable|string',
            'aves_no_corral' => 'nullable|string',
            'peces' => 'nullable|string',
            'crustaceos' => 'nullable|string',
            'sistem_acuaticos' => 'nullable|string',
            'apicolas' => 'nullable|string',
            'enferm_ovin_capri' => 'nullable|string',
            'enferm_ovin_capri_cual' => 'nullable|string',
            'mortali_x_enfermedad' => 'nullable|string',
            'mortali_x_enfermedad_cual' => 'nullable|string',
            'pre_apic_produc_explot' => 'nullable|string',
        ]);
    
        $explotacion = new Tipo_explotacion($request->all());
        $explotacion->save();
    
        return redirect()->route('Seccion4', ['id' => $request->id_predio])
        ->with('success', 'Tipo de explotación creado con éxito.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_predio' => 'required|exists:predios,id',
            'bovinos' => 'nullable|string',
            'bufalinos' => 'nullable|string',
            'porcinos' => 'nullable|string',
            'equinos' => 'nullable|string',
            'ovinos' => 'nullable|string',
            'caprinos' => 'nullable|string',
            'aves_corral' => 'nullable|string',
            'aves_no_corral' => 'nullable|string',
            'peces' => 'nullable|string',
            'crustaceos' => 'nullable|string',
            'sistem_acuaticos' => 'nullable|string',
            'apicolas' => 'nullable|string',
            'enferm_ovin_capri' => 'nullable|string',
            'enferm_ovin_capri_cual' => 'nullable|string',
            'mortali_x_enfermedad' => 'nullable|string',
            'mortali_x_enfermedad_cual' => 'nullable|string',
            'pre_apic_produc_explot' => 'nullable|string',
        ]);
    
        // Buscar el registro existente
        $explotacion = Tipo_explotacion::findOrFail($id);
    
        // Actualizar los datos
        $explotacion->update($request->all());
    
        return redirect()->route('Seccion4', ['id' => $request->id_predio])
                         ->with('success', 'Tipo de explotación actualizado con éxito.');
    }
    


    public function destroy(Tipo_explotacion $tipo_explotacion)
    {
        //
    }
}
