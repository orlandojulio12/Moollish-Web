<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Predios;

class DashboardController extends Controller
{

/**
 * Vista Principal de Dashboards
 */
public function index()
{
    return view('dashboard.index');
}

/**
 * Vista de Navegación - Dashboards Censo
 */
public function censoDashboardsIndex()
{
    return view('dashboard.censo.index');
}

   /**
 * Dashboard Regional - Censo Bovinos
 */
public function censoBovinos(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    // Query base
    $query = DB::table('censo_bovinos')
        ->join('predios', 'censo_bovinos.id_predio', '=', 'predios.id');
    
    // Filtrar por municipio si no es "all"
    if ($municipio != 'all') {
        $query->where('predios.municipio', $municipio);
    }
    
    // Agrupar por municipio - USAR EL CAMPO total_bovinos QUE YA EXISTE
    $censoMunicipal = $query
        ->select(
            'predios.municipio',
            DB::raw('SUM(COALESCE(total_bovinos, 0)) as total'),
            DB::raw('SUM(COALESCE(total_hembras, 0)) as total_hembras'),
            DB::raw('SUM(COALESCE(total_machos, 0)) as total_machos')
        )
        ->groupBy('predios.municipio')
        ->get();
    
    // Total general
    $totalBovinos = $censoMunicipal->sum('total');
    $totalPredios = Predios::count();
    
    // Municipios únicos para filtro
    $municipiosUnicos = Predios::distinct()
        ->whereNotNull('municipio')
        ->pluck('municipio')
        ->sort();
    
    return view('dashboard.censo-bovinos', compact(
        'censoMunicipal',
        'totalBovinos',
        'totalPredios',
        'municipiosUnicos',
        'municipio'
    ));
}

/**
 * Dashboard Regional - Censo Bufalinos
 */
public function censoBuffalinos(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    $query = DB::table('censo_bufalinos')
        ->join('predios', 'censo_bufalinos.id_predio', '=', 'predios.id');
    
    if ($municipio != 'all') {
        $query->where('predios.municipio', $municipio);
    }
    
    $censoMunicipal = $query
        ->select(
            'predios.municipio',
            DB::raw('SUM(COALESCE(total_bufalinos, 0)) as total'),
            DB::raw('SUM(COALESCE(total_hembras, 0)) as total_hembras'),
            DB::raw('SUM(COALESCE(total_machos, 0)) as total_machos')
        )
        ->groupBy('predios.municipio')
        ->get();
    
    $totalAnimales = $censoMunicipal->sum('total');
    $totalPredios = DB::table('censo_bufalinos')->distinct('id_predio')->count('id_predio');
    
    $municipiosUnicos = Predios::distinct()
        ->whereNotNull('municipio')
        ->pluck('municipio')
        ->sort();
    
    return view('dashboard.censo-bufalinos', compact(
        'censoMunicipal',
        'totalAnimales',
        'totalPredios',
        'municipiosUnicos',
        'municipio'
    ));
}

/**
 * Dashboard Regional - Censo Porcinos
 */
public function censoPorcinos(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    $query = DB::table('censo_porcinos')
        ->join('predios', 'censo_porcinos.id_predio', '=', 'predios.id');
    
    if ($municipio != 'all') {
        $query->where('predios.municipio', $municipio);
    }
    
    // PORCINOS solo tiene total_porcinos, NO tiene total_hembras/machos
    $censoMunicipal = $query
        ->select(
            'predios.municipio',
            DB::raw('SUM(COALESCE(total_porcinos, 0)) as total'),
            DB::raw('SUM(COALESCE(lact_hast_30_dias, 0)) as lactancia'),
            DB::raw('SUM(COALESCE(precebo_31_a_60_dias, 0)) as precebo'),
            DB::raw('SUM(COALESCE(lev_ceb_61_180_dias, 0)) as levante_ceba')
        )
        ->groupBy('predios.municipio')
        ->get();
    
    $totalAnimales = $censoMunicipal->sum('total');
    $totalPredios = DB::table('censo_porcinos')->distinct('id_predio')->count('id_predio');
    
    $municipiosUnicos = Predios::distinct()
        ->whereNotNull('municipio')
        ->pluck('municipio')
        ->sort();
    
    return view('dashboard.censo-porcinos', compact(
        'censoMunicipal',
        'totalAnimales',
        'totalPredios',
        'municipiosUnicos',
        'municipio'
    ));
}

/**
 * Dashboard Regional - Censo Équidos
 */
public function censoEquidos(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    $query = DB::table('censo_equidos')
        ->join('predios', 'censo_equidos.id_predio', '=', 'predios.id');
    
    if ($municipio != 'all') {
        $query->where('predios.municipio', $municipio);
    }
    
    // ÉQUIDOS tiene total por tipo: caballar, mular, asnal
    $censoMunicipal = $query
        ->select(
            'predios.municipio',
            DB::raw('SUM(COALESCE(total_equidos, 0)) as total'),
            DB::raw('SUM(COALESCE(total_caballar, 0)) as total_caballar'),
            DB::raw('SUM(COALESCE(total_mular, 0)) as total_mular'),
            DB::raw('SUM(COALESCE(total_asnal, 0)) as total_asnal')
        )
        ->groupBy('predios.municipio')
        ->get();
    
    $totalAnimales = $censoMunicipal->sum('total');
    $totalPredios = DB::table('censo_equidos')->distinct('id_predio')->count('id_predio');
    
    $municipiosUnicos = Predios::distinct()
        ->whereNotNull('municipio')
        ->pluck('municipio')
        ->sort();
    
    return view('dashboard.censo-equidos', compact(
        'censoMunicipal',
        'totalAnimales',
        'totalPredios',
        'municipiosUnicos',
        'municipio'
    ));
}
/**
 * Dashboard Regional - Censo Aves Comerciales
 */
public function censoAvesComerciales(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    $query = DB::table('censo_aves_comerciales')
        ->join('predios', 'censo_aves_comerciales.id_predio', '=', 'predios.id')
        ->leftJoin('tipo_ave_comcercial', 'censo_aves_comerciales.id_tipo_ave_comercial', '=', 'tipo_ave_comcercial.id');
    
    if ($municipio != 'all') {
        $query->where('predios.municipio', $municipio);
    }
    
    // Agrupar por municipio
    $censoMunicipal = $query
        ->select(
            'predios.municipio',
            DB::raw('SUM(COALESCE(num_aves, 0)) as total')
        )
        ->groupBy('predios.municipio')
        ->get();
    
    // Totales por tipo de ave
    $queryTipo = DB::table('censo_aves_comerciales')
        ->join('predios', 'censo_aves_comerciales.id_predio', '=', 'predios.id')
        ->leftJoin('tipo_ave_comcercial', 'censo_aves_comerciales.id_tipo_ave_comercial', '=', 'tipo_ave_comcercial.id');
    
    if ($municipio != 'all') {
        $queryTipo->where('predios.municipio', $municipio);
    }
    
    $censoTipo = $queryTipo
        ->select(
            'tipo_ave_comcercial.nombre as tipo',
            DB::raw('SUM(COALESCE(num_aves, 0)) as total')
        )
        ->groupBy('tipo_ave_comcercial.nombre')
        ->get();
    
    $totalAnimales = $censoMunicipal->sum('total');
    $totalPredios = DB::table('censo_aves_comerciales')->distinct('id_predio')->count('id_predio');
    
    $municipiosUnicos = Predios::distinct()->whereNotNull('municipio')->pluck('municipio')->sort();
    
    return view('dashboard.censo-aves-comerciales', compact(
        'censoMunicipal',
        'censoTipo',
        'totalAnimales',
        'totalPredios',
        'municipiosUnicos',
        'municipio'
    ));
}

/**
 * Dashboard Regional - Censo Aves Traspatio
 */
public function censoAvesTraspatio(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    $query = DB::table('censo_aves_traspatio')
        ->join('predios', 'censo_aves_traspatio.id_predio', '=', 'predios.id')
        ->leftJoin('tipo_ave_transpatio', 'censo_aves_traspatio.id_tipo_ave_transp', '=', 'tipo_ave_transpatio.id');
    
    if ($municipio != 'all') {
        $query->where('predios.municipio', $municipio);
    }
    
    $censoMunicipal = $query
        ->select(
            'predios.municipio',
            DB::raw('SUM(COALESCE(num_aves, 0)) as total')
        )
        ->groupBy('predios.municipio')
        ->get();
    
    // Por tipo
    $queryTipo = DB::table('censo_aves_traspatio')
        ->join('predios', 'censo_aves_traspatio.id_predio', '=', 'predios.id')
        ->leftJoin('tipo_ave_transpatio', 'censo_aves_traspatio.id_tipo_ave_transp', '=', 'tipo_ave_transpatio.id');
    
    if ($municipio != 'all') {
        $queryTipo->where('predios.municipio', $municipio);
    }
    
    $censoTipo = $queryTipo
        ->select(
            'tipo_ave_transpatio.nombre as tipo',
            DB::raw('SUM(COALESCE(num_aves, 0)) as total')
        )
        ->groupBy('tipo_ave_transpatio.nombre')
        ->get();
    
    $totalAnimales = $censoMunicipal->sum('total');
    $totalPredios = DB::table('censo_aves_traspatio')->distinct('id_predio')->count('id_predio');
    
    $municipiosUnicos = Predios::distinct()->whereNotNull('municipio')->pluck('municipio')->sort();
    
    return view('dashboard.censo-aves-traspatio', compact(
        'censoMunicipal',
        'censoTipo',
        'totalAnimales',
        'totalPredios',
        'municipiosUnicos',
        'municipio'
    ));
}

/**
 * Dashboard Regional - Censo Ovinos y Caprinos
 */
public function censoOvinoCaprino(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    $query = DB::table('censo_ovino_caprino')
        ->join('predios', 'censo_ovino_caprino.id_predio', '=', 'predios.id');
    
    if ($municipio != 'all') {
        $query->where('predios.municipio', $municipio);
    }
    
    $censoMunicipal = $query
        ->select(
            'predios.municipio',
            DB::raw('SUM(COALESCE(total_ovinos, 0)) as total_ovinos'),
            DB::raw('SUM(COALESCE(total_caprinos, 0)) as total_caprinos'),
            DB::raw('SUM(COALESCE(total_ovinos, 0) + COALESCE(total_caprinos, 0)) as total')
        )
        ->groupBy('predios.municipio')
        ->get();
    
    $totalAnimales = $censoMunicipal->sum('total');
    $totalOvinos = $censoMunicipal->sum('total_ovinos');
    $totalCaprinos = $censoMunicipal->sum('total_caprinos');
    $totalPredios = DB::table('censo_ovino_caprino')->distinct('id_predio')->count('id_predio');
    
    $municipiosUnicos = Predios::distinct()->whereNotNull('municipio')->pluck('municipio')->sort();
    
    return view('dashboard.censo-ovino-caprino', compact(
        'censoMunicipal',
        'totalAnimales',
        'totalOvinos',
        'totalCaprinos',
        'totalPredios',
        'municipiosUnicos',
        'municipio'
    ));
}

/**
 * Dashboard Regional - Censo Peces
 */
public function censoPeces(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    $query = DB::table('censo_peces')
        ->join('predios', 'censo_peces.id_predio', '=', 'predios.id')
        ->leftJoin('tipo_especie_peces', 'censo_peces.id_tipo_esp_peces', '=', 'tipo_especie_peces.id');
    
    if ($municipio != 'all') {
        $query->where('predios.municipio', $municipio);
    }
    
    // Si total_peces es 0 o null, calcular con la suma de campos
    $censoMunicipal = $query
        ->select(
            'predios.municipio',
            DB::raw('SUM(
                CASE 
                    WHEN COALESCE(total_peces, 0) > 0 THEN total_peces
                    ELSE (COALESCE(ovas, 0) + COALESCE(alevinos, 0) + COALESCE(engorde, 0) + COALESCE(reproductores, 0))
                END
            ) as total')
        )
        ->groupBy('predios.municipio')
        ->get();
    
    // Por especie
    $queryEspecie = DB::table('censo_peces')
        ->join('predios', 'censo_peces.id_predio', '=', 'predios.id')
        ->leftJoin('tipo_especie_peces', 'censo_peces.id_tipo_esp_peces', '=', 'tipo_especie_peces.id');
    
    if ($municipio != 'all') {
        $queryEspecie->where('predios.municipio', $municipio);
    }
    
    $censoEspecie = $queryEspecie
        ->select(
            'tipo_especie_peces.nombre as especie',
            DB::raw('SUM(
                CASE 
                    WHEN COALESCE(total_peces, 0) > 0 THEN total_peces
                    ELSE (COALESCE(ovas, 0) + COALESCE(alevinos, 0) + COALESCE(engorde, 0) + COALESCE(reproductores, 0))
                END
            ) as total')
        )
        ->groupBy('tipo_especie_peces.nombre')
        ->get();
    
    $totalAnimales = $censoMunicipal->sum('total');
    $totalPredios = DB::table('censo_peces')->distinct('id_predio')->count('id_predio');
    
    $municipiosUnicos = Predios::distinct()->whereNotNull('municipio')->pluck('municipio')->sort();
    
    return view('dashboard.censo-peces', compact(
        'censoMunicipal',
        'censoEspecie',
        'totalAnimales',
        'totalPredios',
        'municipiosUnicos',
        'municipio'
    ));
}

/**
 * Dashboard Regional - Censo Crustáceos
 */
public function censoCrustaceos(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    $query = DB::table('censo_cructaceos')
        ->join('predios', 'censo_cructaceos.id_predio', '=', 'predios.id')
        ->leftJoin('tipo_especie_cructaceos', 'censo_cructaceos.id_tipo_esp_cructac', '=', 'tipo_especie_cructaceos.id');
    
    if ($municipio != 'all') {
        $query->where('predios.municipio', $municipio);
    }
    
    // Si total_cructaceos es 0 o null, calcular con la suma de campos
    $censoMunicipal = $query
        ->select(
            'predios.municipio',
            DB::raw('SUM(
                CASE 
                    WHEN COALESCE(total_cructaceos, 0) > 0 THEN total_cructaceos
                    ELSE (COALESCE(nauplinos, 0) + COALESCE(larvicultura, 0) + COALESCE(engorde, 0) + COALESCE(reproductores, 0))
                END
            ) as total')
        )
        ->groupBy('predios.municipio')
        ->get();
    
    // Por especie
    $queryEspecie = DB::table('censo_cructaceos')
        ->join('predios', 'censo_cructaceos.id_predio', '=', 'predios.id')
        ->leftJoin('tipo_especie_cructaceos', 'censo_cructaceos.id_tipo_esp_cructac', '=', 'tipo_especie_cructaceos.id');
    
    if ($municipio != 'all') {
        $queryEspecie->where('predios.municipio', $municipio);
    }
    
    $censoEspecie = $queryEspecie
        ->select(
            'tipo_especie_cructaceos.nombre as especie',
            DB::raw('SUM(
                CASE 
                    WHEN COALESCE(total_cructaceos, 0) > 0 THEN total_cructaceos
                    ELSE (COALESCE(nauplinos, 0) + COALESCE(larvicultura, 0) + COALESCE(engorde, 0) + COALESCE(reproductores, 0))
                END
            ) as total')
        )
        ->groupBy('tipo_especie_cructaceos.nombre')
        ->get();
    
    $totalAnimales = $censoMunicipal->sum('total');
    $totalPredios = DB::table('censo_cructaceos')->distinct('id_predio')->count('id_predio');
    
    $municipiosUnicos = Predios::distinct()->whereNotNull('municipio')->pluck('municipio')->sort();
    
    return view('dashboard.censo-crustaceos', compact(
        'censoMunicipal',
        'censoEspecie',
        'totalAnimales',
        'totalPredios',
        'municipiosUnicos',
        'municipio'
    ));
}

/**
 * Dashboard Consolidado - TODOS LOS CENSOS
 */
public function censoConsolidado(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    // Totales por especie
    $totalBovinos = DB::table('censo_bovinos')
        ->join('predios', 'censo_bovinos.id_predio', '=', 'predios.id')
        ->when($municipio != 'all', function($q) use ($municipio) {
            return $q->where('predios.municipio', $municipio);
        })
        ->sum('total_bovinos');
    
    $totalBuffalinos = DB::table('censo_bufalinos')
        ->join('predios', 'censo_bufalinos.id_predio', '=', 'predios.id')
        ->when($municipio != 'all', function($q) use ($municipio) {
            return $q->where('predios.municipio', $municipio);
        })
        ->sum('total_bufalinos');
    
    $totalPorcinos = DB::table('censo_porcinos')
        ->join('predios', 'censo_porcinos.id_predio', '=', 'predios.id')
        ->when($municipio != 'all', function($q) use ($municipio) {
            return $q->where('predios.municipio', $municipio);
        })
        ->sum('total_porcinos');
    
    $totalEquidos = DB::table('censo_equidos')
        ->join('predios', 'censo_equidos.id_predio', '=', 'predios.id')
        ->when($municipio != 'all', function($q) use ($municipio) {
            return $q->where('predios.municipio', $municipio);
        })
        ->sum('total_equidos');
    
    $totalAvesComerciales = DB::table('censo_aves_comerciales')
        ->join('predios', 'censo_aves_comerciales.id_predio', '=', 'predios.id')
        ->when($municipio != 'all', function($q) use ($municipio) {
            return $q->where('predios.municipio', $municipio);
        })
        ->sum('num_aves');
    
    $totalAvesTraspatio = DB::table('censo_aves_traspatio')
        ->join('predios', 'censo_aves_traspatio.id_predio', '=', 'predios.id')
        ->when($municipio != 'all', function($q) use ($municipio) {
            return $q->where('predios.municipio', $municipio);
        })
        ->sum('num_aves');
    
    $totalOvinos = DB::table('censo_ovino_caprino')
        ->join('predios', 'censo_ovino_caprino.id_predio', '=', 'predios.id')
        ->when($municipio != 'all', function($q) use ($municipio) {
            return $q->where('predios.municipio', $municipio);
        })
        ->sum('total_ovinos');
    
    $totalCaprinos = DB::table('censo_ovino_caprino')
        ->join('predios', 'censo_ovino_caprino.id_predio', '=', 'predios.id')
        ->when($municipio != 'all', function($q) use ($municipio) {
            return $q->where('predios.municipio', $municipio);
        })
        ->sum('total_caprinos');
    
    // PECES - con condicional
    $totalPeces = DB::table('censo_peces')
        ->join('predios', 'censo_peces.id_predio', '=', 'predios.id')
        ->when($municipio != 'all', function($q) use ($municipio) {
            return $q->where('predios.municipio', $municipio);
        })
        ->sum(DB::raw('
            CASE 
                WHEN COALESCE(total_peces, 0) > 0 THEN total_peces
                ELSE (COALESCE(ovas, 0) + COALESCE(alevinos, 0) + COALESCE(engorde, 0) + COALESCE(reproductores, 0))
            END
        '));
    
    // CRUSTÁCEOS - con condicional
    $totalCrustaceos = DB::table('censo_cructaceos')
        ->join('predios', 'censo_cructaceos.id_predio', '=', 'predios.id')
        ->when($municipio != 'all', function($q) use ($municipio) {
            return $q->where('predios.municipio', $municipio);
        })
        ->sum(DB::raw('
            CASE 
                WHEN COALESCE(total_cructaceos, 0) > 0 THEN total_cructaceos
                ELSE (COALESCE(nauplinos, 0) + COALESCE(larvicultura, 0) + COALESCE(engorde, 0) + COALESCE(reproductores, 0))
            END
        '));
    
    // Total general
    $totalGeneral = $totalBovinos + $totalBuffalinos + $totalPorcinos + $totalEquidos + 
                    $totalAvesComerciales + $totalAvesTraspatio + $totalOvinos + 
                    $totalCaprinos + $totalPeces + $totalCrustaceos;
    
    $totalPredios = Predios::count();
    
    $municipiosUnicos = Predios::distinct()->whereNotNull('municipio')->pluck('municipio')->sort();
    
    return view('dashboard.censo-consolidado', compact(
        'totalBovinos',
        'totalBuffalinos',
        'totalPorcinos',
        'totalEquidos',
        'totalAvesComerciales',
        'totalAvesTraspatio',
        'totalOvinos',
        'totalCaprinos',
        'totalPeces',
        'totalCrustaceos',
        'totalGeneral',
        'totalPredios',
        'municipiosUnicos',
        'municipio'
    ));
}


/**
 * Dashboard Comparativo - Bovinos
 */
public function comparativoBovinos(Request $request)
{
    $prediosIds = $request->get('predios', []);
    
    // Todos los predios para el selector
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    $comparacion = [];
    
    if (!empty($prediosIds)) {
        foreach ($prediosIds as $predioId) {
            $predio = Predios::find($predioId);
            
            if ($predio) {
                $censo = DB::table('censo_bovinos')
                    ->where('id_predio', $predioId)
                    ->first();
                
                if ($censo) {
                    $comparacion[] = [
                        'predio_id' => $predio->id,
                        'nombre' => $predio->nombre_predio,
                        'municipio' => $predio->municipio,
                        'total' => $censo->total_bovinos ?? 0,
                        'hembras' => $censo->total_hembras ?? 0,
                        'machos' => $censo->total_machos ?? 0
                    ];
                }
            }
        }
    }
    
    return view('dashboard.comparativo.bovinos', compact('todosPredios', 'comparacion', 'prediosIds'));
}

/**
 * Dashboard Comparativo - Bufalinos
 */
public function comparativoBuffalinos(Request $request)
{
    $prediosIds = $request->get('predios', []);
    
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    $comparacion = [];
    
    if (!empty($prediosIds)) {
        foreach ($prediosIds as $predioId) {
            $predio = Predios::find($predioId);
            
            if ($predio) {
                $censo = DB::table('censo_bufalinos')
                    ->where('id_predio', $predioId)
                    ->first();
                
                if ($censo) {
                    $comparacion[] = [
                        'predio_id' => $predio->id,
                        'nombre' => $predio->nombre_predio,
                        'municipio' => $predio->municipio,
                        'total' => $censo->total_bufalinos ?? 0,
                        'hembras' => $censo->total_hembras ?? 0,
                        'machos' => $censo->total_machos ?? 0
                    ];
                }
            }
        }
    }
    
    return view('dashboard.comparativo.bufalinos', compact('todosPredios', 'comparacion', 'prediosIds'));
}

/**
 * Dashboard Comparativo - Porcinos
 */
public function comparativoPorcinos(Request $request)
{
    $prediosIds = $request->get('predios', []);
    
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    $comparacion = [];
    
    if (!empty($prediosIds)) {
        foreach ($prediosIds as $predioId) {
            $predio = Predios::find($predioId);
            
            if ($predio) {
                $censo = DB::table('censo_porcinos')
                    ->where('id_predio', $predioId)
                    ->first();
                
                if ($censo) {
                    $comparacion[] = [
                        'predio_id' => $predio->id,
                        'nombre' => $predio->nombre_predio,
                        'municipio' => $predio->municipio,
                        'total' => $censo->total_porcinos ?? 0,
                        'lactancia' => $censo->lact_hast_30_dias ?? 0,
                        'precebo' => $censo->precebo_31_a_60_dias ?? 0,
                        'levante' => $censo->lev_ceb_61_180_dias ?? 0
                    ];
                }
            }
        }
    }
    
    return view('dashboard.comparativo.porcinos', compact('todosPredios', 'comparacion', 'prediosIds'));
}

/**
 * Dashboard Comparativo - Équidos
 */
public function comparativoEquidos(Request $request)
{
    $prediosIds = $request->get('predios', []);
    
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    $comparacion = [];
    
    if (!empty($prediosIds)) {
        foreach ($prediosIds as $predioId) {
            $predio = Predios::find($predioId);
            
            if ($predio) {
                $censo = DB::table('censo_equidos')
                    ->where('id_predio', $predioId)
                    ->first();
                
                if ($censo) {
                    $comparacion[] = [
                        'predio_id' => $predio->id,
                        'nombre' => $predio->nombre_predio,
                        'municipio' => $predio->municipio,
                        'total' => $censo->total_equidos ?? 0,
                        'caballar' => $censo->total_caballar ?? 0,
                        'mular' => $censo->total_mular ?? 0,
                        'asnal' => $censo->total_asnal ?? 0
                    ];
                }
            }
        }
    }
    
    return view('dashboard.comparativo.equidos', compact('todosPredios', 'comparacion', 'prediosIds'));
}

/**
 * Dashboard Comparativo - Ovinos y Caprinos
 */
public function comparativoOvinoCaprino(Request $request)
{
    $prediosIds = $request->get('predios', []);
    
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    $comparacion = [];
    
    if (!empty($prediosIds)) {
        foreach ($prediosIds as $predioId) {
            $predio = Predios::find($predioId);
            
            if ($predio) {
                $censo = DB::table('censo_ovino_caprino')
                    ->where('id_predio', $predioId)
                    ->first();
                
                if ($censo) {
                    $comparacion[] = [
                        'predio_id' => $predio->id,
                        'nombre' => $predio->nombre_predio,
                        'municipio' => $predio->municipio,
                        'total' => ($censo->total_ovinos ?? 0) + ($censo->total_caprinos ?? 0),
                        'ovinos' => $censo->total_ovinos ?? 0,
                        'caprinos' => $censo->total_caprinos ?? 0
                    ];
                }
            }
        }
    }
    
    return view('dashboard.comparativo.ovino-caprino', compact('todosPredios', 'comparacion', 'prediosIds'));
}

/**
 * Dashboard Comparativo - Peces
 */
public function comparativoPeces(Request $request)
{
    $prediosIds = $request->get('predios', []);
    
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    $comparacion = [];
    
    if (!empty($prediosIds)) {
        foreach ($prediosIds as $predioId) {
            $predio = Predios::find($predioId);
            
            if ($predio) {
                $censo = DB::table('censo_peces')
                    ->where('id_predio', $predioId)
                    ->first();
                
                if ($censo) {
                    // Calcular total
                    $total = ($censo->total_peces ?? 0) > 0 
                        ? $censo->total_peces 
                        : ($censo->ovas ?? 0) + ($censo->alevinos ?? 0) + ($censo->engorde ?? 0) + ($censo->reproductores ?? 0);
                    
                    $comparacion[] = [
                        'predio_id' => $predio->id,
                        'nombre' => $predio->nombre_predio,
                        'municipio' => $predio->municipio,
                        'total' => $total,
                        'ovas' => $censo->ovas ?? 0,
                        'alevinos' => $censo->alevinos ?? 0,
                        'engorde' => $censo->engorde ?? 0,
                        'reproductores' => $censo->reproductores ?? 0
                    ];
                }
            }
        }
    }
    
    return view('dashboard.comparativo.peces', compact('todosPredios', 'comparacion', 'prediosIds'));
}

/**
 * Dashboard Individual - Bovinos
 */
public function individualBovinos(Request $request)
{
    $predioId = $request->get('predio');
    
    // Todos los predios para el selector
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    $datosPredio = null;
    $promedioMunicipal = null;
    $comparacion = null;
    
    if ($predioId) {
        $predio = Predios::find($predioId);
        
        if ($predio) {
            // Datos del predio seleccionado
            $censo = DB::table('censo_bovinos')
                ->where('id_predio', $predioId)
                ->first();
            
            if ($censo) {
                $datosPredio = [
                    'predio_id' => $predio->id,
                    'nombre' => $predio->nombre_predio,
                    'municipio' => $predio->municipio,
                    'total' => $censo->total_bovinos ?? 0,
                    'hembras' => $censo->total_hembras ?? 0,
                    'machos' => $censo->total_machos ?? 0
                ];
                
                // Promedio del municipio
                $promedioMunicipal = DB::table('censo_bovinos')
                    ->join('predios', 'censo_bovinos.id_predio', '=', 'predios.id')
                    ->where('predios.municipio', $predio->municipio)
                    ->select(
                        DB::raw('AVG(COALESCE(total_bovinos, 0)) as avg_total'),
                        DB::raw('AVG(COALESCE(total_hembras, 0)) as avg_hembras'),
                        DB::raw('AVG(COALESCE(total_machos, 0)) as avg_machos')
                    )
                    ->first();
                
                // Comparación
                $comparacion = [
                    'total_diff' => $datosPredio['total'] - $promedioMunicipal->avg_total,
                    'total_percent' => $promedioMunicipal->avg_total > 0 
                        ? round((($datosPredio['total'] - $promedioMunicipal->avg_total) / $promedioMunicipal->avg_total) * 100, 1) 
                        : 0,
                    'hembras_diff' => $datosPredio['hembras'] - $promedioMunicipal->avg_hembras,
                    'machos_diff' => $datosPredio['machos'] - $promedioMunicipal->avg_machos
                ];
            }
        }
    }
    
    return view('dashboard.individual.bovinos', compact(
        'todosPredios',
        'datosPredio',
        'promedioMunicipal',
        'comparacion',
        'predioId'
    ));
}

/**
 * Dashboard Individual - Bufalinos
 */
public function individualBuffalinos(Request $request)
{
    $predioId = $request->get('predio');
    
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    $datosPredio = null;
    $promedioMunicipal = null;
    $comparacion = null;
    
    if ($predioId) {
        $predio = Predios::find($predioId);
        
        if ($predio) {
            $censo = DB::table('censo_bufalinos')
                ->where('id_predio', $predioId)
                ->first();
            
            if ($censo) {
                $datosPredio = [
                    'predio_id' => $predio->id,
                    'nombre' => $predio->nombre_predio,
                    'municipio' => $predio->municipio,
                    'total' => $censo->total_bufalinos ?? 0,
                    'hembras' => $censo->total_hembras ?? 0,
                    'machos' => $censo->total_machos ?? 0
                ];
                
                $promedioMunicipal = DB::table('censo_bufalinos')
                    ->join('predios', 'censo_bufalinos.id_predio', '=', 'predios.id')
                    ->where('predios.municipio', $predio->municipio)
                    ->select(
                        DB::raw('AVG(COALESCE(total_bufalinos, 0)) as avg_total'),
                        DB::raw('AVG(COALESCE(total_hembras, 0)) as avg_hembras'),
                        DB::raw('AVG(COALESCE(total_machos, 0)) as avg_machos')
                    )
                    ->first();
                
                $comparacion = [
                    'total_diff' => $datosPredio['total'] - $promedioMunicipal->avg_total,
                    'total_percent' => $promedioMunicipal->avg_total > 0 
                        ? round((($datosPredio['total'] - $promedioMunicipal->avg_total) / $promedioMunicipal->avg_total) * 100, 1) 
                        : 0,
                    'hembras_diff' => $datosPredio['hembras'] - $promedioMunicipal->avg_hembras,
                    'machos_diff' => $datosPredio['machos'] - $promedioMunicipal->avg_machos
                ];
            }
        }
    }
    
    return view('dashboard.individual.bufalinos', compact(
        'todosPredios',
        'datosPredio',
        'promedioMunicipal',
        'comparacion',
        'predioId'
    ));
}

/**
 * Dashboard Individual - Porcinos
 */
public function individualPorcinos(Request $request)
{
    $predioId = $request->get('predio');
    
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    $datosPredio = null;
    $promedioMunicipal = null;
    $comparacion = null;
    
    if ($predioId) {
        $predio = Predios::find($predioId);
        
        if ($predio) {
            $censo = DB::table('censo_porcinos')
                ->where('id_predio', $predioId)
                ->first();
            
            if ($censo) {
                $datosPredio = [
                    'predio_id' => $predio->id,
                    'nombre' => $predio->nombre_predio,
                    'municipio' => $predio->municipio,
                    'total' => $censo->total_porcinos ?? 0,
                    'lactancia' => $censo->lact_hast_30_dias ?? 0,
                    'precebo' => $censo->precebo_31_a_60_dias ?? 0,
                    'levante' => $censo->lev_ceb_61_180_dias ?? 0
                ];
                
                $promedioMunicipal = DB::table('censo_porcinos')
                    ->join('predios', 'censo_porcinos.id_predio', '=', 'predios.id')
                    ->where('predios.municipio', $predio->municipio)
                    ->select(
                        DB::raw('AVG(COALESCE(total_porcinos, 0)) as avg_total'),
                        DB::raw('AVG(COALESCE(lact_hast_30_dias, 0)) as avg_lactancia'),
                        DB::raw('AVG(COALESCE(precebo_31_a_60_dias, 0)) as avg_precebo'),
                        DB::raw('AVG(COALESCE(lev_ceb_61_180_dias, 0)) as avg_levante')
                    )
                    ->first();
                
                $comparacion = [
                    'total_diff' => $datosPredio['total'] - $promedioMunicipal->avg_total,
                    'total_percent' => $promedioMunicipal->avg_total > 0 
                        ? round((($datosPredio['total'] - $promedioMunicipal->avg_total) / $promedioMunicipal->avg_total) * 100, 1) 
                        : 0
                ];
            }
        }
    }
    
    return view('dashboard.individual.porcinos', compact(
        'todosPredios',
        'datosPredio',
        'promedioMunicipal',
        'comparacion',
        'predioId'
    ));
}

/**
 * Dashboard Individual - Équidos
 */
public function individualEquidos(Request $request)
{
    $predioId = $request->get('predio');
    
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    $datosPredio = null;
    $promedioMunicipal = null;
    $comparacion = null;
    
    if ($predioId) {
        $predio = Predios::find($predioId);
        
        if ($predio) {
            $censo = DB::table('censo_equidos')
                ->where('id_predio', $predioId)
                ->first();
            
            if ($censo) {
                $datosPredio = [
                    'predio_id' => $predio->id,
                    'nombre' => $predio->nombre_predio,
                    'municipio' => $predio->municipio,
                    'total' => $censo->total_equidos ?? 0,
                    'caballar' => $censo->total_caballar ?? 0,
                    'mular' => $censo->total_mular ?? 0,
                    'asnal' => $censo->total_asnal ?? 0
                ];
                
                $promedioMunicipal = DB::table('censo_equidos')
                    ->join('predios', 'censo_equidos.id_predio', '=', 'predios.id')
                    ->where('predios.municipio', $predio->municipio)
                    ->select(
                        DB::raw('AVG(COALESCE(total_equidos, 0)) as avg_total'),
                        DB::raw('AVG(COALESCE(total_caballar, 0)) as avg_caballar'),
                        DB::raw('AVG(COALESCE(total_mular, 0)) as avg_mular'),
                        DB::raw('AVG(COALESCE(total_asnal, 0)) as avg_asnal')
                    )
                    ->first();
                
                $comparacion = [
                    'total_diff' => $datosPredio['total'] - $promedioMunicipal->avg_total,
                    'total_percent' => $promedioMunicipal->avg_total > 0 
                        ? round((($datosPredio['total'] - $promedioMunicipal->avg_total) / $promedioMunicipal->avg_total) * 100, 1) 
                        : 0
                ];
            }
        }
    }
    
    return view('dashboard.individual.equidos', compact(
        'todosPredios',
        'datosPredio',
        'promedioMunicipal',
        'comparacion',
        'predioId'
    ));
}

/**
 * Dashboard Individual - Ovinos y Caprinos
 */
public function individualOvinoCaprino(Request $request)
{
    $predioId = $request->get('predio');
    
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    $datosPredio = null;
    $promedioMunicipal = null;
    $comparacion = null;
    
    if ($predioId) {
        $predio = Predios::find($predioId);
        
        if ($predio) {
            $censo = DB::table('censo_ovino_caprino')
                ->where('id_predio', $predioId)
                ->first();
            
            if ($censo) {
                $datosPredio = [
                    'predio_id' => $predio->id,
                    'nombre' => $predio->nombre_predio,
                    'municipio' => $predio->municipio,
                    'total' => ($censo->total_ovinos ?? 0) + ($censo->total_caprinos ?? 0),
                    'ovinos' => $censo->total_ovinos ?? 0,
                    'caprinos' => $censo->total_caprinos ?? 0
                ];
                
                $promedioMunicipal = DB::table('censo_ovino_caprino')
                    ->join('predios', 'censo_ovino_caprino.id_predio', '=', 'predios.id')
                    ->where('predios.municipio', $predio->municipio)
                    ->select(
                        DB::raw('AVG(COALESCE(total_ovinos, 0) + COALESCE(total_caprinos, 0)) as avg_total'),
                        DB::raw('AVG(COALESCE(total_ovinos, 0)) as avg_ovinos'),
                        DB::raw('AVG(COALESCE(total_caprinos, 0)) as avg_caprinos')
                    )
                    ->first();
                
                $comparacion = [
                    'total_diff' => $datosPredio['total'] - $promedioMunicipal->avg_total,
                    'total_percent' => $promedioMunicipal->avg_total > 0 
                        ? round((($datosPredio['total'] - $promedioMunicipal->avg_total) / $promedioMunicipal->avg_total) * 100, 1) 
                        : 0
                ];
            }
        }
    }
    
    return view('dashboard.individual.ovino-caprino', compact(
        'todosPredios',
        'datosPredio',
        'promedioMunicipal',
        'comparacion',
        'predioId'
    ));
}

/**
 * Dashboard Individual - Peces
 */
public function individualPeces(Request $request)
{
    $predioId = $request->get('predio');
    
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    $datosPredio = null;
    $promedioMunicipal = null;
    $comparacion = null;
    
    if ($predioId) {
        $predio = Predios::find($predioId);
        
        if ($predio) {
            $censo = DB::table('censo_peces')
                ->where('id_predio', $predioId)
                ->first();
            
            if ($censo) {
                // Calcular total
                $total = ($censo->total_peces ?? 0) > 0 
                    ? $censo->total_peces 
                    : ($censo->ovas ?? 0) + ($censo->alevinos ?? 0) + ($censo->engorde ?? 0) + ($censo->reproductores ?? 0);
                
                $datosPredio = [
                    'predio_id' => $predio->id,
                    'nombre' => $predio->nombre_predio,
                    'municipio' => $predio->municipio,
                    'total' => $total,
                    'ovas' => $censo->ovas ?? 0,
                    'alevinos' => $censo->alevinos ?? 0,
                    'engorde' => $censo->engorde ?? 0,
                    'reproductores' => $censo->reproductores ?? 0
                ];
                
                $promedioMunicipal = DB::table('censo_peces')
                    ->join('predios', 'censo_peces.id_predio', '=', 'predios.id')
                    ->where('predios.municipio', $predio->municipio)
                    ->select(
                        DB::raw('AVG(
                            CASE 
                                WHEN COALESCE(total_peces, 0) > 0 THEN total_peces
                                ELSE (COALESCE(ovas, 0) + COALESCE(alevinos, 0) + COALESCE(engorde, 0) + COALESCE(reproductores, 0))
                            END
                        ) as avg_total')
                    )
                    ->first();
                
                $comparacion = [
                    'total_diff' => $datosPredio['total'] - $promedioMunicipal->avg_total,
                    'total_percent' => $promedioMunicipal->avg_total > 0 
                        ? round((($datosPredio['total'] - $promedioMunicipal->avg_total) / $promedioMunicipal->avg_total) * 100, 1) 
                        : 0
                ];
            }
        }
    }
    
    return view('dashboard.individual.peces', compact(
        'todosPredios',
        'datosPredio',
        'promedioMunicipal',
        'comparacion',
        'predioId'
    ));
}

/**
 * Dashboard Regional - Información de Predios (CONSOLIDADO)
 */
public function infoPrediosConsolidado(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    // Base query con filtro de municipio
    $queryPredios = Predios::query();
    if ($municipio != 'all') {
        $queryPredios->where('municipio', $municipio);
    }
    
    $prediosIds = $queryPredios->pluck('id');
    $totalPredios = $prediosIds->count();
    
    // 1. ÁREAS - Por tipo de área y hectáreas
    $areasPorTipo = DB::table('areas')
        ->join('tipo_areas', 'areas.id_tipo_area', '=', 'tipo_areas.id')
        ->whereIn('areas.id_predio', $prediosIds)
        ->where('areas.tipo_medidas', 'Hectáreas')
        ->select(
            'tipo_areas.nombre_area',
            DB::raw('COUNT(DISTINCT areas.id_predio) as predios'),
            DB::raw('SUM(CASE 
                WHEN areas.medidas REGEXP "^[0-9 ]+$" 
                THEN CAST(REPLACE(REPLACE(areas.medidas, " ", "."), ",", ".") AS DECIMAL(10,2))
                ELSE 0 
            END) as hectareas_totales')
        )
        ->groupBy('tipo_areas.nombre_area')
        ->orderBy('hectareas_totales', 'desc')
        ->get();
    
    $totalPrediosConAreas = DB::table('areas')
        ->whereIn('id_predio', $prediosIds)
        ->distinct('id_predio')
        ->count('id_predio');
    
    // 2. TIERRAS Y AGUAS - Adopción de prácticas
    $totalPrediosTierraAgua = DB::table('info_tierra_agua')
        ->whereIn('id_predio', $prediosIds)
        ->distinct('id_predio')
        ->count('id_predio');
    
    $practicasAgua = DB::table('info_tierra_agua')
        ->whereIn('id_predio', $prediosIds)
        ->select(
            DB::raw('COUNT(DISTINCT CASE WHEN suelos_predominantes IS NOT NULL AND suelos_predominantes != "" THEN id_predio END) as con_info_suelos'),
            DB::raw('COUNT(DISTINCT CASE WHEN drenaje IS NOT NULL AND drenaje != "" THEN id_predio END) as con_drenaje'),
            DB::raw('COUNT(DISTINCT CASE WHEN fuente_calidad_agua IS NOT NULL AND fuente_calidad_agua != "" THEN id_predio END) as con_fuente_agua'),
            DB::raw('COUNT(DISTINCT CASE WHEN manejo_cuencas_nac_agua IS NOT NULL AND manejo_cuencas_nac_agua != "" THEN id_predio END) as maneja_cuencas')
        )
        ->first();
    
    // 3. MANEJO DE PASTOS - Adopción de tecnologías (normalizar mayúsculas)
    $totalPrediosPastos = DB::table('man_past_potr_cerc')
        ->whereIn('id_predio', $prediosIds)
        ->distinct('id_predio')
        ->count('id_predio');
    
    $manejoPasstos = DB::table('man_past_potr_cerc')
        ->whereIn('id_predio', $prediosIds)
        ->select(
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(r_fertilazion_potreros) = "SI" THEN id_predio END) as fertiliza_potreros'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(r_control_plagas) = "SI" THEN id_predio END) as controla_plagas'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(r_control_maleza) = "SI" THEN id_predio END) as controla_maleza'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(div_potreros) = "SI" THEN id_predio END) as tiene_division_potreros'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(r_fertilazion_potreros_produc) = "SI" THEN id_predio END) as fertiliza_productivos'),
            DB::raw('COUNT(DISTINCT CASE WHEN cercas IS NOT NULL AND cercas != "" THEN id_predio END) as tiene_cercas')
        )
        ->first();
    
    // 4. MANEJO DE GANADO - Prácticas implementadas
    $totalPrediosGanado = DB::table('man_gen_ganado')
        ->whereIn('id_predio', $prediosIds)
        ->distinct('id_predio')
        ->count('id_predio');
    
    $manejoGanado = DB::table('man_gen_ganado')
        ->whereIn('id_predio', $prediosIds)
        ->select(
            DB::raw('COUNT(DISTINCT CASE WHEN ident_animales IS NOT NULL AND ident_animales != "" THEN id_predio END) as identifica_animales'),
            DB::raw('COUNT(DISTINCT CASE WHEN sistema_cria_ternero IS NOT NULL AND sistema_cria_ternero != "" THEN id_predio END) as sistema_cria'),
            DB::raw('COUNT(DISTINCT CASE WHEN control_parasito_extern IS NOT NULL AND control_parasito_extern != "" THEN id_predio END) as control_parasitos'),
            DB::raw('COUNT(DISTINCT CASE WHEN pesaje_animal IS NOT NULL AND pesaje_animal != "" THEN id_predio END) as hace_pesaje'),
            DB::raw('COUNT(DISTINCT CASE WHEN aliment_ternero IS NOT NULL AND aliment_ternero != "" THEN id_predio END) as alimenta_terneros'),
            DB::raw('COUNT(DISTINCT CASE WHEN sumin_sal IS NOT NULL AND sumin_sal != "" THEN id_predio END) as suministra_sal')
        )
        ->first();
    
    // 5. ASPECTOS AMBIENTALES - Adopción
    $totalPrediosAmbiental = DB::table('inform_aspect_med_ambient')
        ->whereIn('id_predio', $prediosIds)
        ->distinct('id_predio')
        ->count('id_predio');
    
    $aspectosAmbientales = DB::table('inform_aspect_med_ambient')
        ->whereIn('id_predio', $prediosIds)
        ->select(
            DB::raw('COUNT(DISTINCT CASE WHEN dispos_aguas_servid IS NOT NULL AND dispos_aguas_servid != "" THEN id_predio END) as maneja_aguas'),
            DB::raw('COUNT(DISTINCT CASE WHEN dispos_excrement_bovinos IS NOT NULL AND dispos_excrement_bovinos != "" THEN id_predio END) as maneja_excrementos'),
            DB::raw('COUNT(DISTINCT CASE WHEN manejo_basuras IS NOT NULL AND manejo_basuras != "" THEN id_predio END) as maneja_basuras'),
            DB::raw('COUNT(DISTINCT CASE WHEN manejo_empaq_produc_quimic IS NOT NULL AND manejo_empaq_produc_quimic != "" THEN id_predio END) as maneja_quimicos')
        )
        ->first();
    
    // 6. EQUIPOS E INSTALACIONES - Por tipo
    $totalPrediosEquipos = DB::table('instalaciones_equipos')
        ->whereIn('id_predio', $prediosIds)
        ->distinct('id_predio')
        ->count('id_predio');
    
    $equiposInstalaciones = DB::table('instalaciones_equipos')
        ->join('tipos_instalaciones_equipos', 'instalaciones_equipos.id_tipos_equipos', '=', 'tipos_instalaciones_equipos.id')
        ->whereIn('instalaciones_equipos.id_predio', $prediosIds)
        ->select(
            'tipos_instalaciones_equipos.nombre_tipo',
            DB::raw('COUNT(DISTINCT instalaciones_equipos.id_predio) as predios_con_equipo')
        )
        ->groupBy('tipos_instalaciones_equipos.nombre_tipo')
        ->orderBy('predios_con_equipo', 'desc')
        ->get();
    
    // 7. GESTIÓN DE INFORMACIÓN
    $totalPrediosGestion = DB::table('gestion_informacion')
        ->whereIn('id_predio', $prediosIds)
        ->distinct('id_predio')
        ->count('id_predio');
    
    $gestionInfo = DB::table('gestion_informacion')
        ->whereIn('id_predio', $prediosIds)
        ->select(
            DB::raw('COUNT(DISTINCT CASE WHEN los_registros_son IS NOT NULL AND los_registros_son != "" THEN id_predio END) as lleva_registros'),
            DB::raw('COUNT(DISTINCT CASE WHEN calcula_indicadores IS NOT NULL AND calcula_indicadores != "" THEN id_predio END) as calcula_indicadores'),
            DB::raw('COUNT(DISTINCT CASE WHEN la_informacion_es IS NOT NULL AND la_informacion_es != "" THEN id_predio END) as usa_informacion'),
            DB::raw('COUNT(DISTINCT CASE WHEN donde_regis_info_finca IS NOT NULL AND donde_regis_info_finca != "" THEN id_predio END) as registra_info')
        )
        ->first();
    
    $municipiosUnicos = Predios::distinct()->whereNotNull('municipio')->pluck('municipio')->sort();
    
    return view('dashboard.info-predios-consolidado', compact(
        'totalPredios',
        'areasPorTipo',
        'totalPrediosConAreas',
        'practicasAgua',
        'totalPrediosTierraAgua',
        'manejoPasstos',
        'totalPrediosPastos',
        'manejoGanado',
        'totalPrediosGanado',
        'aspectosAmbientales',
        'totalPrediosAmbiental',
        'equiposInstalaciones',
        'totalPrediosEquipos',
        'gestionInfo',
        'totalPrediosGestion',
        'municipiosUnicos',
        'municipio'
    ));
}

/**
 * Dashboard Individual - Información de Predios
 */
public function infoPrediosIndividual(Request $request)
{
    $predioId = $request->get('predio');
    
    // Obtener todos los predios para el selector
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    if (!$predioId) {
        return view('dashboard.info-predios-individual', compact('todosPredios', 'predioId'));
    }
    
    $predio = Predios::findOrFail($predioId);
    $municipio = $predio->municipio;
    
    // Predios del mismo municipio para comparar
    $prediosMunicipio = Predios::where('municipio', $municipio)->pluck('id');
    
    // ============================================
    // 1. ÁREAS
    // ============================================
    $miAreas = DB::table('areas')
        ->join('tipo_areas', 'areas.id_tipo_area', '=', 'tipo_areas.id')
        ->where('areas.id_predio', $predioId)
        ->select(
            'tipo_areas.nombre_area',
            DB::raw('SUM(CASE 
                WHEN areas.medidas REGEXP "^[0-9 ]+$" 
                THEN CAST(REPLACE(REPLACE(areas.medidas, " ", "."), ",", ".") AS DECIMAL(10,2))
                ELSE 0 
            END) as hectareas')
        )
        ->groupBy('tipo_areas.nombre_area')
        ->get();
    
    $promedioAreas = DB::table('areas')
        ->join('tipo_areas', 'areas.id_tipo_area', '=', 'tipo_areas.id')
        ->whereIn('areas.id_predio', $prediosMunicipio)
        ->select(
            'tipo_areas.nombre_area',
            DB::raw('AVG(CASE 
                WHEN areas.medidas REGEXP "^[0-9 ]+$" 
                THEN CAST(REPLACE(REPLACE(areas.medidas, " ", "."), ",", ".") AS DECIMAL(10,2))
                ELSE 0 
            END) as hectareas_promedio')
        )
        ->groupBy('tipo_areas.nombre_area')
        ->get()
        ->keyBy('nombre_area');
    
    // ============================================
    // 2. TIERRAS Y AGUAS
    // ============================================
    $miTierraAgua = DB::table('info_tierra_agua')
        ->where('id_predio', $predioId)
        ->select(
            DB::raw('COUNT(CASE WHEN suelos_predominantes IS NOT NULL AND suelos_predominantes != "" THEN 1 END) as tiene_suelos'),
            DB::raw('COUNT(CASE WHEN drenaje IS NOT NULL AND drenaje != "" THEN 1 END) as tiene_drenaje'),
            DB::raw('COUNT(CASE WHEN fuente_calidad_agua IS NOT NULL AND fuente_calidad_agua != "" THEN 1 END) as tiene_agua'),
            DB::raw('COUNT(CASE WHEN manejo_cuencas_nac_agua IS NOT NULL AND manejo_cuencas_nac_agua != "" THEN 1 END) as tiene_cuencas')
        )
        ->first();
    
    $promedioTierraAgua = DB::table('info_tierra_agua')
        ->whereIn('id_predio', $prediosMunicipio)
        ->select(
            DB::raw('AVG(CASE WHEN suelos_predominantes IS NOT NULL AND suelos_predominantes != "" THEN 1 ELSE 0 END) * 100 as prom_suelos'),
            DB::raw('AVG(CASE WHEN drenaje IS NOT NULL AND drenaje != "" THEN 1 ELSE 0 END) * 100 as prom_drenaje'),
            DB::raw('AVG(CASE WHEN fuente_calidad_agua IS NOT NULL AND fuente_calidad_agua != "" THEN 1 ELSE 0 END) * 100 as prom_agua'),
            DB::raw('AVG(CASE WHEN manejo_cuencas_nac_agua IS NOT NULL AND manejo_cuencas_nac_agua != "" THEN 1 ELSE 0 END) * 100 as prom_cuencas')
        )
        ->first();
    
    // ============================================
    // 3. PASTOS
    // ============================================
    $miPastos = DB::table('man_past_potr_cerc')
        ->where('id_predio', $predioId)
        ->select(
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(r_fertilazion_potreros) = "SI" THEN id END) as fertiliza'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(r_control_plagas) = "SI" THEN id END) as plagas'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(r_control_maleza) = "SI" THEN id END) as maleza'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(div_potreros) = "SI" THEN id END) as division'),
            DB::raw('COUNT(DISTINCT CASE WHEN cercas IS NOT NULL AND cercas != "" THEN id END) as cercas')
        )
        ->first();
    
    $promedioPastos = DB::table('man_past_potr_cerc')
        ->whereIn('id_predio', $prediosMunicipio)
        ->select(
            DB::raw('AVG(CASE WHEN UPPER(r_fertilazion_potreros) = "SI" THEN 1 ELSE 0 END) * 100 as prom_fertiliza'),
            DB::raw('AVG(CASE WHEN UPPER(r_control_plagas) = "SI" THEN 1 ELSE 0 END) * 100 as prom_plagas'),
            DB::raw('AVG(CASE WHEN UPPER(r_control_maleza) = "SI" THEN 1 ELSE 0 END) * 100 as prom_maleza'),
            DB::raw('AVG(CASE WHEN UPPER(div_potreros) = "SI" THEN 1 ELSE 0 END) * 100 as prom_division'),
            DB::raw('AVG(CASE WHEN cercas IS NOT NULL AND cercas != "" THEN 1 ELSE 0 END) * 100 as prom_cercas')
        )
        ->first();
    
    // ============================================
    // 4. GANADO
    // ============================================
    $miGanado = DB::table('man_gen_ganado')
        ->where('id_predio', $predioId)
        ->select(
            DB::raw('COUNT(CASE WHEN ident_animales IS NOT NULL AND ident_animales != "" THEN 1 END) as identifica'),
            DB::raw('COUNT(CASE WHEN sistema_cria_ternero IS NOT NULL AND sistema_cria_ternero != "" THEN 1 END) as cria'),
            DB::raw('COUNT(CASE WHEN control_parasito_extern IS NOT NULL AND control_parasito_extern != "" THEN 1 END) as parasitos'),
            DB::raw('COUNT(CASE WHEN pesaje_animal IS NOT NULL AND pesaje_animal != "" THEN 1 END) as pesaje'),
            DB::raw('COUNT(CASE WHEN sumin_sal IS NOT NULL AND sumin_sal != "" THEN 1 END) as sal')
        )
        ->first();
    
    $promedioGanado = DB::table('man_gen_ganado')
        ->whereIn('id_predio', $prediosMunicipio)
        ->select(
            DB::raw('AVG(CASE WHEN ident_animales IS NOT NULL AND ident_animales != "" THEN 1 ELSE 0 END) * 100 as prom_identifica'),
            DB::raw('AVG(CASE WHEN sistema_cria_ternero IS NOT NULL AND sistema_cria_ternero != "" THEN 1 ELSE 0 END) * 100 as prom_cria'),
            DB::raw('AVG(CASE WHEN control_parasito_extern IS NOT NULL AND control_parasito_extern != "" THEN 1 ELSE 0 END) * 100 as prom_parasitos'),
            DB::raw('AVG(CASE WHEN pesaje_animal IS NOT NULL AND pesaje_animal != "" THEN 1 ELSE 0 END) * 100 as prom_pesaje'),
            DB::raw('AVG(CASE WHEN sumin_sal IS NOT NULL AND sumin_sal != "" THEN 1 ELSE 0 END) * 100 as prom_sal')
        )
        ->first();
    
    // ============================================
    // 5. AMBIENTAL
    // ============================================
    $miAmbiental = DB::table('inform_aspect_med_ambient')
        ->where('id_predio', $predioId)
        ->select(
            DB::raw('COUNT(CASE WHEN dispos_aguas_servid IS NOT NULL AND dispos_aguas_servid != "" THEN 1 END) as aguas'),
            DB::raw('COUNT(CASE WHEN dispos_excrement_bovinos IS NOT NULL AND dispos_excrement_bovinos != "" THEN 1 END) as excrementos'),
            DB::raw('COUNT(CASE WHEN manejo_basuras IS NOT NULL AND manejo_basuras != "" THEN 1 END) as basuras'),
            DB::raw('COUNT(CASE WHEN manejo_empaq_produc_quimic IS NOT NULL AND manejo_empaq_produc_quimic != "" THEN 1 END) as quimicos')
        )
        ->first();
    
    $promedioAmbiental = DB::table('inform_aspect_med_ambient')
        ->whereIn('id_predio', $prediosMunicipio)
        ->select(
            DB::raw('AVG(CASE WHEN dispos_aguas_servid IS NOT NULL AND dispos_aguas_servid != "" THEN 1 ELSE 0 END) * 100 as prom_aguas'),
            DB::raw('AVG(CASE WHEN dispos_excrement_bovinos IS NOT NULL AND dispos_excrement_bovinos != "" THEN 1 ELSE 0 END) * 100 as prom_excrementos'),
            DB::raw('AVG(CASE WHEN manejo_basuras IS NOT NULL AND manejo_basuras != "" THEN 1 ELSE 0 END) * 100 as prom_basuras'),
            DB::raw('AVG(CASE WHEN manejo_empaq_produc_quimic IS NOT NULL AND manejo_empaq_produc_quimic != "" THEN 1 ELSE 0 END) * 100 as prom_quimicos')
        )
        ->first();
    
    // ============================================
    // 6. GESTIÓN
    // ============================================
    $miGestion = DB::table('gestion_informacion')
        ->where('id_predio', $predioId)
        ->select(
            DB::raw('COUNT(CASE WHEN los_registros_son IS NOT NULL AND los_registros_son != "" THEN 1 END) as registros'),
            DB::raw('COUNT(CASE WHEN calcula_indicadores IS NOT NULL AND calcula_indicadores != "" THEN 1 END) as indicadores'),
            DB::raw('COUNT(CASE WHEN la_informacion_es IS NOT NULL AND la_informacion_es != "" THEN 1 END) as usa_info'),
            DB::raw('COUNT(CASE WHEN donde_regis_info_finca IS NOT NULL AND donde_regis_info_finca != "" THEN 1 END) as registra')
        )
        ->first();
    
    $promedioGestion = DB::table('gestion_informacion')
        ->whereIn('id_predio', $prediosMunicipio)
        ->select(
            DB::raw('AVG(CASE WHEN los_registros_son IS NOT NULL AND los_registros_son != "" THEN 1 ELSE 0 END) * 100 as prom_registros'),
            DB::raw('AVG(CASE WHEN calcula_indicadores IS NOT NULL AND calcula_indicadores != "" THEN 1 ELSE 0 END) * 100 as prom_indicadores'),
            DB::raw('AVG(CASE WHEN la_informacion_es IS NOT NULL AND la_informacion_es != "" THEN 1 ELSE 0 END) * 100 as prom_usa_info'),
            DB::raw('AVG(CASE WHEN donde_regis_info_finca IS NOT NULL AND donde_regis_info_finca != "" THEN 1 ELSE 0 END) * 100 as prom_registra')
        )
        ->first();
    
    // ============================================
    // CALCULAR SCORES Y RECOMENDACIONES
    // ============================================
    
    // Score Tierras y Aguas
    $miScoreTierra = (($miTierraAgua->tiene_suelos > 0 ? 25 : 0) + 
                      ($miTierraAgua->tiene_drenaje > 0 ? 25 : 0) + 
                      ($miTierraAgua->tiene_agua > 0 ? 25 : 0) + 
                      ($miTierraAgua->tiene_cuencas > 0 ? 25 : 0));
    $promedioScoreTierra = ($promedioTierraAgua->prom_suelos + $promedioTierraAgua->prom_drenaje + 
                            $promedioTierraAgua->prom_agua + $promedioTierraAgua->prom_cuencas) / 4;
    
    // Score Pastos
    $miScorePastos = (($miPastos->fertiliza > 0 ? 20 : 0) + 
                      ($miPastos->plagas > 0 ? 20 : 0) + 
                      ($miPastos->maleza > 0 ? 20 : 0) + 
                      ($miPastos->division > 0 ? 20 : 0) + 
                      ($miPastos->cercas > 0 ? 20 : 0));
    $promedioScorePastos = ($promedioPastos->prom_fertiliza + $promedioPastos->prom_plagas + 
                            $promedioPastos->prom_maleza + $promedioPastos->prom_division + 
                            $promedioPastos->prom_cercas) / 5;
    
    // Score Ganado
    $miScoreGanado = (($miGanado->identifica > 0 ? 20 : 0) + 
                      ($miGanado->cria > 0 ? 20 : 0) + 
                      ($miGanado->parasitos > 0 ? 20 : 0) + 
                      ($miGanado->pesaje > 0 ? 20 : 0) + 
                      ($miGanado->sal > 0 ? 20 : 0));
    $promedioScoreGanado = ($promedioGanado->prom_identifica + $promedioGanado->prom_cria + 
                            $promedioGanado->prom_parasitos + $promedioGanado->prom_pesaje + 
                            $promedioGanado->prom_sal) / 5;
    
    // Score Ambiental
    $miScoreAmbiental = (($miAmbiental->aguas > 0 ? 25 : 0) + 
                         ($miAmbiental->excrementos > 0 ? 25 : 0) + 
                         ($miAmbiental->basuras > 0 ? 25 : 0) + 
                         ($miAmbiental->quimicos > 0 ? 25 : 0));
    $promedioScoreAmbiental = ($promedioAmbiental->prom_aguas + $promedioAmbiental->prom_excrementos + 
                               $promedioAmbiental->prom_basuras + $promedioAmbiental->prom_quimicos) / 4;
    
    // Score Gestión
    $miScoreGestion = (($miGestion->registros > 0 ? 25 : 0) + 
                       ($miGestion->indicadores > 0 ? 25 : 0) + 
                       ($miGestion->usa_info > 0 ? 25 : 0) + 
                       ($miGestion->registra > 0 ? 25 : 0));
    $promedioScoreGestion = ($promedioGestion->prom_registros + $promedioGestion->prom_indicadores + 
                             $promedioGestion->prom_usa_info + $promedioGestion->prom_registra) / 4;
    
    // Score General
    $scoreGeneral = round(($miScoreTierra + $miScorePastos + $miScoreGanado + $miScoreAmbiental + $miScoreGestion) / 5);
    
    // Sistema de Recomendaciones
    $recomendaciones = [];
    $fortalezas = [];
    
    // Tierras y Aguas
    if ($miScoreTierra < 50) {
        if ($miTierraAgua->tiene_suelos == 0) $recomendaciones[] = ['area' => 'Tierras y Aguas', 'texto' => 'Realizar análisis de suelos para conocer las características del terreno'];
        if ($miTierraAgua->tiene_drenaje == 0) $recomendaciones[] = ['area' => 'Tierras y Aguas', 'texto' => 'Implementar sistema de drenaje adecuado'];
        if ($miTierraAgua->tiene_agua == 0) $recomendaciones[] = ['area' => 'Tierras y Aguas', 'texto' => 'Identificar y mejorar fuentes de agua disponibles'];
    } else if ($miScoreTierra >= 75) {
        $fortalezas[] = 'Gestión de Tierras y Aguas';
    }
    
    // Pastos
    if ($miScorePastos < 50) {
        if ($miPastos->fertiliza == 0) $recomendaciones[] = ['area' => 'Manejo de Pastos', 'texto' => 'Implementar programa de fertilización de potreros'];
        if ($miPastos->plagas == 0) $recomendaciones[] = ['area' => 'Manejo de Pastos', 'texto' => 'Establecer control integrado de plagas'];
        if ($miPastos->maleza == 0) $recomendaciones[] = ['area' => 'Manejo de Pastos', 'texto' => 'Realizar control regular de malezas'];
        if ($miPastos->division == 0) $recomendaciones[] = ['area' => 'Manejo de Pastos', 'texto' => 'Dividir potreros para rotación de pastoreo'];
    } else if ($miScorePastos >= 75) {
        $fortalezas[] = 'Manejo de Pastos y Potreros';
    }
    
    // Ganado
    if ($miScoreGanado < 50) {
        if ($miGanado->identifica == 0) $recomendaciones[] = ['area' => 'Manejo del Ganado', 'texto' => 'Implementar sistema de identificación de animales'];
        if ($miGanado->parasitos == 0) $recomendaciones[] = ['area' => 'Manejo del Ganado', 'texto' => 'Establecer programa sanitario de control de parásitos'];
        if ($miGanado->pesaje == 0) $recomendaciones[] = ['area' => 'Manejo del Ganado', 'texto' => 'Realizar pesajes periódicos para monitorear productividad'];
    } else if ($miScoreGanado >= 75) {
        $fortalezas[] = 'Manejo del Ganado';
    }
    
    // Ambiental
    if ($miScoreAmbiental < 50) {
        if ($miAmbiental->aguas == 0) $recomendaciones[] = ['area' => 'Gestión Ambiental', 'texto' => 'Implementar sistema de manejo de aguas servidas'];
        if ($miAmbiental->excrementos == 0) $recomendaciones[] = ['area' => 'Gestión Ambiental', 'texto' => 'Establecer compostaje o biodigestión de excrementos'];
        if ($miAmbiental->basuras == 0) $recomendaciones[] = ['area' => 'Gestión Ambiental', 'texto' => 'Crear plan de manejo de residuos sólidos'];
    } else if ($miScoreAmbiental >= 75) {
        $fortalezas[] = 'Gestión Ambiental';
    }
    
    // Gestión
    if ($miScoreGestion < 50) {
        if ($miGestion->registros == 0) $recomendaciones[] = ['area' => 'Gestión de Información', 'texto' => 'Iniciar registro sistemático de actividades productivas'];
        if ($miGestion->indicadores == 0) $recomendaciones[] = ['area' => 'Gestión de Información', 'texto' => 'Calcular indicadores productivos y reproductivos'];
        if ($miGestion->usa_info == 0) $recomendaciones[] = ['area' => 'Gestión de Información', 'texto' => 'Usar la información registrada para toma de decisiones'];
    } else if ($miScoreGestion >= 75) {
        $fortalezas[] = 'Gestión de Información';
    }
    
    return view('dashboard.info-predios-individual', compact(
        'todosPredios',
        'predioId',
        'predio',
        'scoreGeneral',
        'miScoreTierra', 'promedioScoreTierra',
        'miScorePastos', 'promedioScorePastos',
        'miScoreGanado', 'promedioScoreGanado',
        'miScoreAmbiental', 'promedioScoreAmbiental',
        'miScoreGestion', 'promedioScoreGestion',
        'recomendaciones',
        'fortalezas',
        'miTierraAgua', 'promedioTierraAgua',
        'miPastos', 'promedioPastos',
        'miGanado', 'promedioGanado',
        'miAmbiental', 'promedioAmbiental',
        'miGestion', 'promedioGestion'
    ));
}

/**
 * Dashboard Regional - BPG (Buenas Prácticas Ganaderas)
 */
public function bgpConsolidado(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    // Base query con filtro de municipio
    $queryPredios = Predios::query();
    if ($municipio != 'all') {
        $queryPredios->where('municipio', $municipio);
    }
    
    $prediosIds = $queryPredios->pluck('id');
    $totalPredios = $prediosIds->count();
    
    // Definir las secciones por rango de ID
    $secciones = [
        ['nombre' => 'Sanidad Animal', 'inicio' => 1, 'fin' => 5],
        ['nombre' => 'Identificación', 'inicio' => 6, 'fin' => 7],
        ['nombre' => 'Bioseguridad', 'inicio' => 8, 'fin' => 12],
        ['nombre' => 'BPMV - Medicamentos', 'inicio' => 13, 'fin' => 24],
        ['nombre' => 'BPAA - Alimentación', 'inicio' => 25, 'fin' => 31],
        ['nombre' => 'Saneamiento', 'inicio' => 32, 'fin' => 38],
        ['nombre' => 'Bienestar Animal', 'inicio' => 39, 'fin' => 47],
        ['nombre' => 'Personal', 'inicio' => 48, 'fin' => 100],
    ];
    
    $datosPorSeccion = [];
    
    foreach ($secciones as $seccion) {
        $datos = DB::table('informacion_bgp')
            ->whereIn('id_predio', $prediosIds)
            ->whereBetween('id_tipos_bgp', [$seccion['inicio'], $seccion['fin']])
            ->select(
                DB::raw('COUNT(DISTINCT id_predio) as predios_respondieron'),
                DB::raw('COUNT(DISTINCT CASE WHEN UPPER(estado) = "SI" THEN id_predio END) as predios_con_si'),
                DB::raw('SUM(CASE WHEN UPPER(estado) = "SI" THEN 1 ELSE 0 END) as total_si'),
                DB::raw('SUM(CASE WHEN UPPER(estado) = "NO" THEN 1 ELSE 0 END) as total_no'),
                DB::raw('SUM(CASE WHEN UPPER(estado) = "NA" THEN 1 ELSE 0 END) as total_na'),
                DB::raw('COUNT(*) as total_respuestas')
            )
            ->first();
        
        // Calcular porcentajes
        $total_validas = $datos->total_si + $datos->total_no;
        $porcentaje_cumplimiento = $total_validas > 0 ? round(($datos->total_si / $total_validas) * 100) : 0;
        
        $datosPorSeccion[] = [
            'nombre' => $seccion['nombre'],
            'predios_respondieron' => $datos->predios_respondieron,
            'predios_con_si' => $datos->predios_con_si,
            'total_si' => $datos->total_si,
            'total_no' => $datos->total_no,
            'total_na' => $datos->total_na,
            'total_respuestas' => $datos->total_respuestas,
            'porcentaje_cumplimiento' => $porcentaje_cumplimiento,
            'total_practicas' => $seccion['fin'] - $seccion['inicio'] + 1,
        ];
    }
    
    // Prácticas con menor cumplimiento (críticas)
    $practicasCriticas = DB::table('informacion_bgp')
        ->join('tipos_informacion_bgp', 'informacion_bgp.id_tipos_bgp', '=', 'tipos_informacion_bgp.id')
        ->whereIn('informacion_bgp.id_predio', $prediosIds)
        ->select(
            'tipos_informacion_bgp.nombre',
            DB::raw('SUM(CASE WHEN UPPER(informacion_bgp.estado) = "SI" THEN 1 ELSE 0 END) as total_si'),
            DB::raw('SUM(CASE WHEN UPPER(informacion_bgp.estado) = "NO" THEN 1 ELSE 0 END) as total_no'),
            DB::raw('COUNT(*) as total_respuestas'),
            DB::raw('ROUND((SUM(CASE WHEN UPPER(informacion_bgp.estado) = "SI" THEN 1 ELSE 0 END) / COUNT(*)) * 100) as porcentaje_si')
        )
        ->groupBy('tipos_informacion_bgp.nombre')
        ->having('porcentaje_si', '<', 50)
        ->orderBy('porcentaje_si', 'asc')
        ->limit(10)
        ->get();
    
    // Calcular score general
    $scoreGeneral = round(collect($datosPorSeccion)->avg('porcentaje_cumplimiento'));
    
    $municipiosUnicos = Predios::distinct()->whereNotNull('municipio')->pluck('municipio')->sort();
    
    return view('dashboard.bgp-consolidado', compact(
        'totalPredios',
        'datosPorSeccion',
        'practicasCriticas',
        'scoreGeneral',
        'municipiosUnicos',
        'municipio'
    ));
}

/**
 * Dashboard Individual - BPG (Buenas Prácticas Ganaderas)
 */
/**
 * Dashboard Individual - BPG (Buenas Prácticas Ganaderas)
 */
public function bgpIndividual(Request $request)
{
    $predioId = $request->get('predio');
    
    // Obtener todos los predios para el selector
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    if (!$predioId) {
        return view('dashboard.bgp-individual', compact('todosPredios', 'predioId'));
    }
    
    $predio = Predios::findOrFail($predioId);
    $municipio = $predio->municipio;
    
    // Predios del mismo municipio para comparar
    $prediosMunicipio = Predios::where('municipio', $municipio)->pluck('id');
    
    // Definir las secciones
    $secciones = [
        ['nombre' => 'Sanidad Animal', 'inicio' => 1, 'fin' => 5],
        ['nombre' => 'Identificación', 'inicio' => 6, 'fin' => 7],
        ['nombre' => 'Bioseguridad', 'inicio' => 8, 'fin' => 12],
        ['nombre' => 'BPMV - Medicamentos', 'inicio' => 13, 'fin' => 24],
        ['nombre' => 'BPAA - Alimentación', 'inicio' => 25, 'fin' => 31],
        ['nombre' => 'Saneamiento', 'inicio' => 32, 'fin' => 38],
        ['nombre' => 'Bienestar Animal', 'inicio' => 39, 'fin' => 47],
        ['nombre' => 'Personal', 'inicio' => 48, 'fin' => 100],
    ];
    
    $datosPorSeccion = [];
    $recomendaciones = [];
    $fortalezas = [];
    
    foreach ($secciones as $seccion) {
        
        // Total de prácticas por sección
        $totalPracticas = $seccion['fin'] - $seccion['inicio'] + 1;

        // Datos del predio
        $misDatos = DB::table('informacion_bgp')
            ->where('id_predio', $predioId)
            ->whereBetween('id_tipos_bgp', [$seccion['inicio'], $seccion['fin']])
            ->select(
                DB::raw('SUM(CASE WHEN UPPER(estado) = "SI" THEN 1 ELSE 0 END) as mis_si'),
                DB::raw('SUM(CASE WHEN UPPER(estado) = "NO" THEN 1 ELSE 0 END) as mis_no'),
                DB::raw('SUM(CASE WHEN UPPER(estado) = "NA" THEN 1 ELSE 0 END) as mis_na'),
                DB::raw('COUNT(*) as mis_total')
            )
            ->first();
        
        // Asegurar valores numéricos (evita null)
        $mis_si = $misDatos->mis_si ?? 0;
        $mis_no = $misDatos->mis_no ?? 0;
        $mis_na = $misDatos->mis_na ?? 0;

        // Promedio municipal
        $promedioMunicipal = DB::table('informacion_bgp')
            ->whereIn('id_predio', $prediosMunicipio)
            ->whereBetween('id_tipos_bgp', [$seccion['inicio'], $seccion['fin']])
            ->select(
                DB::raw('AVG(CASE WHEN UPPER(estado) = "SI" THEN 1 ELSE 0 END) * 100 as prom_si'),
                DB::raw('AVG(CASE WHEN UPPER(estado) = "NO" THEN 1 ELSE 0 END) * 100 as prom_no')
            )
            ->first();
        
        // Calcular porcentajes
        $mis_validas = $mis_si + $mis_no;
        $mi_porcentaje = $mis_validas > 0 ? round(($mis_si / $mis_validas) * 100) : 0;
        $promedio_porcentaje = round($promedioMunicipal->prom_si ?? 0);

        $datosPorSeccion[] = [
            'nombre' => $seccion['nombre'],
            'mis_si' => $mis_si,
            'mis_no' => $mis_no,
            'mis_na' => $mis_na,
            'mi_porcentaje' => $mi_porcentaje,
            'promedio_porcentaje' => $promedio_porcentaje,
            'total_practicas' => $totalPracticas,
        ];
        
        // Generar recomendaciones
        if ($mi_porcentaje < 60 && $mis_no > 0) {
            $recomendaciones[] = [
                'area' => $seccion['nombre'],
                'texto' => "Mejorar cumplimiento: tienes {$mis_no} prácticas sin cumplir de {$totalPracticas}"
            ];
        }
        
        // Identificar fortalezas
        if ($mi_porcentaje >= 80) {
            $fortalezas[] = $seccion['nombre'];
        }
    }
    
    // Calcular score general
    $scoreGeneral = round(collect($datosPorSeccion)->avg('mi_porcentaje'));
    $promedioGeneral = round(collect($datosPorSeccion)->avg('promedio_porcentaje'));
    
    // Prácticas específicas que NO cumple
    $practicasNoCumple = DB::table('informacion_bgp')
        ->join('tipos_informacion_bgp', 'informacion_bgp.id_tipos_bgp', '=', 'tipos_informacion_bgp.id')
        ->where('informacion_bgp.id_predio', $predioId)
        ->where('informacion_bgp.estado', 'No')
        ->select('tipos_informacion_bgp.nombre')
        ->limit(10)
        ->get();
    
    return view('dashboard.bgp-individual', compact(
        'todosPredios',
        'predioId',
        'predio',
        'scoreGeneral',
        'promedioGeneral',
        'datosPorSeccion',
        'recomendaciones',
        'fortalezas',
        'practicasNoCumple'
    ));
}
/**
 * Dashboard Regional - Riesgo Epidemiológico
 */
public function riesgoEpidemiologicoConsolidado(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    // Base query con filtro de municipio
    $queryPredios = Predios::query();
    if ($municipio != 'all') {
        $queryPredios->where('municipio', $municipio);
    }
    
    $prediosIds = $queryPredios->pluck('id');
    $totalPredios = $prediosIds->count();
    
    // ============================================
    // 1. TIPO DE EXPLOTACIÓN
    // ============================================
    $tiposExplotacion = DB::table('tp_explotacion')
        ->whereIn('id_predio', $prediosIds)
        ->select(
            DB::raw('COUNT(DISTINCT id_predio) as predios_respondieron'),
            DB::raw('COUNT(DISTINCT CASE WHEN bovinos IS NOT NULL AND bovinos != "" THEN id_predio END) as con_bovinos'),
            DB::raw('COUNT(DISTINCT CASE WHEN bufalinos IS NOT NULL AND bufalinos != "" THEN id_predio END) as con_bufalinos'),
            DB::raw('COUNT(DISTINCT CASE WHEN porcinos IS NOT NULL AND porcinos != "" THEN id_predio END) as con_porcinos'),
            DB::raw('COUNT(DISTINCT CASE WHEN equinos IS NOT NULL AND equinos != "" THEN id_predio END) as con_equinos'),
            DB::raw('COUNT(DISTINCT CASE WHEN ovinos IS NOT NULL AND ovinos != "" THEN id_predio END) as con_ovinos'),
            DB::raw('COUNT(DISTINCT CASE WHEN caprinos IS NOT NULL AND caprinos != "" THEN id_predio END) as con_caprinos'),
            DB::raw('COUNT(DISTINCT CASE WHEN aves_corral IS NOT NULL AND aves_corral != "" THEN id_predio END) as con_aves_corral'),
            DB::raw('COUNT(DISTINCT CASE WHEN peces IS NOT NULL AND peces != "" THEN id_predio END) as con_peces'),
            DB::raw('COUNT(DISTINCT CASE WHEN apicolas IS NOT NULL AND apicolas != "" THEN id_predio END) as con_apicolas')
        )
        ->first();
    
    // ============================================
    // 2. INFORMACIÓN EPIDEMIOLÓGICA
    // ============================================
    $infoEpidemiologica = DB::table('infor_epidemiologica')
        ->whereIn('id_predio', $prediosIds)
        ->select(
            DB::raw('COUNT(DISTINCT id_predio) as predios_respondieron'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(anim_enferm_control) = "SI" THEN id_predio END) as con_animales_enfermos'),
            DB::raw('SUM(CASE WHEN anim_enferm_control_cant IS NOT NULL AND anim_enferm_control_cant > 0 THEN anim_enferm_control_cant ELSE 0 END) as total_animales_enfermos'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(toma_muestra) = "SI" THEN id_predio END) as con_toma_muestras'),
            DB::raw('SUM(CASE WHEN toma_muestra_numeros IS NOT NULL AND toma_muestra_numeros > 0 THEN toma_muestra_numeros ELSE 0 END) as total_muestras')
        )
        ->first();
    
    // ============================================
    // 3. CARACTERIZACIÓN DE RIESGO
    // ============================================
    $caracterizacionRiesgo = DB::table('caracterizacion_riesgo')
        ->whereIn('id_predio', $prediosIds)
        ->select(
            DB::raw('COUNT(DISTINCT id_predio) as predios_respondieron'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(colinda_establecim_riesgo) = "SI" THEN id_predio END) as colinda_riesgo'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(sacrif_anim_pred) = "SI" THEN id_predio END) as sacrifica_animales'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(asistencia_tecnica) = "SI" THEN id_predio END) as con_asistencia_tecnica'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(trabajan_otr_explotacion) = "SI" THEN id_predio END) as trabajadores_otras_explotaciones'),
            DB::raw('AVG(CASE WHEN num_trabajadores IS NOT NULL AND num_trabajadores > 0 THEN num_trabajadores ELSE 0 END) as promedio_trabajadores')
        )
        ->first();
    
    // ============================================
    // 4. VISITA A PREDIOS DE RIESGO
    // ============================================
    $visitaPredios = DB::table('visita_predios_riesgo')
        ->whereIn('id_predio', $prediosIds)
        ->select(
            DB::raw('COUNT(DISTINCT id_predio) as predios_visitados'),
            DB::raw('COUNT(DISTINCT CASE WHEN UPPER(toma_muestras) = "SI" THEN id_predio END) as con_toma_muestras'),
            DB::raw('SUM(CASE WHEN num_muestras IS NOT NULL AND num_muestras > 0 THEN num_muestras ELSE 0 END) as total_muestras_visita')
        )
        ->first();
    
    // Calcular Score General de Riesgo
    $factoresRiesgo = [
        'colinda_riesgo' => $caracterizacionRiesgo->colinda_riesgo ?? 0,
        'sacrifica_animales' => $caracterizacionRiesgo->sacrifica_animales ?? 0,
        'animales_enfermos' => $infoEpidemiologica->con_animales_enfermos ?? 0,
        'trabajadores_otras' => $caracterizacionRiesgo->trabajadores_otras_explotaciones ?? 0,
    ];
    
    $totalFactoresRiesgo = array_sum($factoresRiesgo);
    $prediosConRiesgo = $totalPredios > 0 ? round(($totalFactoresRiesgo / ($totalPredios * 4)) * 100) : 0;
    
    $municipiosUnicos = Predios::distinct()->whereNotNull('municipio')->pluck('municipio')->sort();
    
    return view('dashboard.riesgo-epidemiologico-consolidado', compact(
        'totalPredios',
        'tiposExplotacion',
        'infoEpidemiologica',
        'caracterizacionRiesgo',
        'visitaPredios',
        'prediosConRiesgo',
        'municipiosUnicos',
        'municipio'
    ));
}
/**
 * Dashboard Individual - Riesgo Epidemiológico
 */
public function riesgoEpidemiologicoIndividual(Request $request)
{
    $predioId = $request->get('predio');
    
    // Obtener todos los predios para el selector
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    if (!$predioId) {
        return view('dashboard.riesgo-epidemiologico-individual', compact('todosPredios', 'predioId'));
    }
    
    $predio = Predios::findOrFail($predioId);
    $municipio = $predio->municipio;
    
    // Predios del mismo municipio para comparar
    $prediosMunicipio = Predios::where('municipio', $municipio)->pluck('id');
    
    // ============================================
    // 1. TIPO DE EXPLOTACIÓN
    // ============================================
    $miTipoExplotacion = DB::table('tp_explotacion')
        ->where('id_predio', $predioId)
        ->select(
            DB::raw('COUNT(CASE WHEN bovinos IS NOT NULL AND bovinos != "" THEN 1 END) as tiene_bovinos'),
            DB::raw('COUNT(CASE WHEN bufalinos IS NOT NULL AND bufalinos != "" THEN 1 END) as tiene_bufalinos'),
            DB::raw('COUNT(CASE WHEN porcinos IS NOT NULL AND porcinos != "" THEN 1 END) as tiene_porcinos'),
            DB::raw('COUNT(CASE WHEN equinos IS NOT NULL AND equinos != "" THEN 1 END) as tiene_equinos'),
            DB::raw('COUNT(CASE WHEN ovinos IS NOT NULL AND ovinos != "" THEN 1 END) as tiene_ovinos'),
            DB::raw('COUNT(CASE WHEN caprinos IS NOT NULL AND caprinos != "" THEN 1 END) as tiene_caprinos'),
            DB::raw('COUNT(CASE WHEN aves_corral IS NOT NULL AND aves_corral != "" THEN 1 END) as tiene_aves_corral'),
            DB::raw('COUNT(CASE WHEN peces IS NOT NULL AND peces != "" THEN 1 END) as tiene_peces'),
            DB::raw('COUNT(CASE WHEN apicolas IS NOT NULL AND apicolas != "" THEN 1 END) as tiene_apicolas')
        )
        ->first();
    
    // Calcular diversidad de especies (cuántos tipos tiene)
    $miDiversidad = ($miTipoExplotacion->tiene_bovinos > 0 ? 1 : 0) +
                    ($miTipoExplotacion->tiene_bufalinos > 0 ? 1 : 0) +
                    ($miTipoExplotacion->tiene_porcinos > 0 ? 1 : 0) +
                    ($miTipoExplotacion->tiene_equinos > 0 ? 1 : 0) +
                    ($miTipoExplotacion->tiene_ovinos > 0 ? 1 : 0) +
                    ($miTipoExplotacion->tiene_caprinos > 0 ? 1 : 0) +
                    ($miTipoExplotacion->tiene_aves_corral > 0 ? 1 : 0) +
                    ($miTipoExplotacion->tiene_peces > 0 ? 1 : 0) +
                    ($miTipoExplotacion->tiene_apicolas > 0 ? 1 : 0);
    
    // Promedio de diversidad municipal
    $promedioDiversidad = DB::table('tp_explotacion')
        ->whereIn('id_predio', $prediosMunicipio)
        ->selectRaw('id_predio,
            (CASE WHEN bovinos IS NOT NULL AND bovinos != "" THEN 1 ELSE 0 END +
             CASE WHEN bufalinos IS NOT NULL AND bufalinos != "" THEN 1 ELSE 0 END +
             CASE WHEN porcinos IS NOT NULL AND porcinos != "" THEN 1 ELSE 0 END +
             CASE WHEN equinos IS NOT NULL AND equinos != "" THEN 1 ELSE 0 END +
             CASE WHEN ovinos IS NOT NULL AND ovinos != "" THEN 1 ELSE 0 END +
             CASE WHEN caprinos IS NOT NULL AND caprinos != "" THEN 1 ELSE 0 END +
             CASE WHEN aves_corral IS NOT NULL AND aves_corral != "" THEN 1 ELSE 0 END +
             CASE WHEN peces IS NOT NULL AND peces != "" THEN 1 ELSE 0 END +
             CASE WHEN apicolas IS NOT NULL AND apicolas != "" THEN 1 ELSE 0 END) as diversidad')
        ->get()
        ->avg('diversidad');
    
    // ============================================
    // 2. INFORMACIÓN EPIDEMIOLÓGICA
    // ============================================
    $miInfoEpidemiologica = DB::table('infor_epidemiologica')
        ->where('id_predio', $predioId)
        ->select(
            DB::raw('COUNT(CASE WHEN UPPER(anim_enferm_control) = "SI" THEN 1 END) as tiene_enfermos'),
            DB::raw('SUM(CASE WHEN anim_enferm_control_cant IS NOT NULL THEN anim_enferm_control_cant ELSE 0 END) as total_enfermos'),
            DB::raw('COUNT(CASE WHEN UPPER(toma_muestra) = "SI" THEN 1 END) as tiene_muestras')
        )
        ->first();
    
    $promedioEnfermos = DB::table('infor_epidemiologica')
        ->whereIn('id_predio', $prediosMunicipio)
        ->selectRaw('AVG(CASE WHEN UPPER(anim_enferm_control) = "SI" THEN 1 ELSE 0 END) * 100 as prom_enfermos')
        ->first();
    
    // ============================================
    // 3. CARACTERIZACIÓN DE RIESGO
    // ============================================
    $miCaracterizacionRiesgo = DB::table('caracterizacion_riesgo')
        ->where('id_predio', $predioId)
        ->select(
            DB::raw('COUNT(CASE WHEN UPPER(colinda_establecim_riesgo) = "SI" THEN 1 END) as colinda_riesgo'),
            DB::raw('COUNT(CASE WHEN UPPER(sacrif_anim_pred) = "SI" THEN 1 END) as sacrifica'),
            DB::raw('COUNT(CASE WHEN UPPER(trabajan_otr_explotacion) = "SI" THEN 1 END) as trabajadores_otras'),
            DB::raw('COUNT(CASE WHEN UPPER(asistencia_tecnica) = "SI" THEN 1 END) as tiene_asistencia'),
            DB::raw('MAX(num_trabajadores) as num_trabajadores')
        )
        ->first();
    
    // Calcular factores de riesgo (0-4)
    $misFactoresRiesgo = ($miCaracterizacionRiesgo->colinda_riesgo > 0 ? 1 : 0) +
                         ($miCaracterizacionRiesgo->sacrifica > 0 ? 1 : 0) +
                         ($miCaracterizacionRiesgo->trabajadores_otras > 0 ? 1 : 0) +
                         ($miCaracterizacionRiesgo->tiene_asistencia == 0 ? 1 : 0);
    
    // Promedio municipal de factores de riesgo
    $promedioFactoresRiesgo = DB::table('caracterizacion_riesgo')
        ->whereIn('id_predio', $prediosMunicipio)
        ->selectRaw('AVG(
            (CASE WHEN UPPER(colinda_establecim_riesgo) = "SI" THEN 1 ELSE 0 END +
             CASE WHEN UPPER(sacrif_anim_pred) = "SI" THEN 1 ELSE 0 END +
             CASE WHEN UPPER(trabajan_otr_explotacion) = "SI" THEN 1 ELSE 0 END +
             CASE WHEN UPPER(asistencia_tecnica) = "NO" OR asistencia_tecnica IS NULL THEN 1 ELSE 0 END)
        ) as promedio')
        ->first();
    
    // ============================================
    // 4. VISITA DE VIGILANCIA
    // ============================================
    $miVisita = DB::table('visita_predios_riesgo')
        ->where('id_predio', $predioId)
        ->select(
            DB::raw('COUNT(*) as fue_visitado'),
            DB::raw('COUNT(CASE WHEN UPPER(toma_muestras) = "SI" THEN 1 END) as con_muestras')
        )
        ->first();
    
    $promedioVisitas = DB::table('visita_predios_riesgo')
        ->whereIn('id_predio', $prediosMunicipio)
        ->selectRaw('AVG(CASE WHEN id IS NOT NULL THEN 1 ELSE 0 END) * 100 as prom_visitados')
        ->first();
    
    // ============================================
    // CALCULAR SCORE DE RIESGO (0-100)
    // ============================================
    // Mayor diversidad = menor riesgo
    $scoreDiversidad = 100 - (($miDiversidad / 9) * 100);
    
    // Tiene enfermos = mayor riesgo
    $scoreEpidemiologico = ($miInfoEpidemiologica->tiene_enfermos > 0) ? 100 : 0;
    
    // Más factores = mayor riesgo
    $scoreRiesgo = ($misFactoresRiesgo / 4) * 100;
    
    // No visitado = mayor riesgo
    $scoreVigilancia = ($miVisita->fue_visitado > 0) ? 0 : 50;
    
    // Score general (promedio ponderado)
    $scoreGeneral = round(($scoreEpidemiologico * 0.4 + $scoreRiesgo * 0.4 + $scoreVigilancia * 0.2));
    
    // Score promedio municipal
    $scorePromedioMunicipal = round(
        (($promedioEnfermos->prom_enfermos ?? 0) * 0.4) +
        ((($promedioFactoresRiesgo->promedio ?? 0) / 4) * 100 * 0.4) +
        (100 - ($promedioVisitas->prom_visitados ?? 0)) * 0.2
    );
    
    // ============================================
    // RECOMENDACIONES Y FORTALEZAS
    // ============================================
    $recomendaciones = [];
    $fortalezas = [];
    
    if ($miInfoEpidemiologica->tiene_enfermos > 0) {
        $recomendaciones[] = [
            'area' => 'Situación Sanitaria',
            'texto' => "Tiene {$miInfoEpidemiologica->total_enfermos} animales con signología de enfermedad. Realizar diagnóstico confirmatorio."
        ];
    }
    
    if ($miCaracterizacionRiesgo->colinda_riesgo > 0) {
        $recomendaciones[] = [
            'area' => 'Bioseguridad',
            'texto' => 'Colinda con establecimiento de riesgo. Implementar medidas de bioseguridad: cercas perimetrales, control de acceso, desinfección.'
        ];
    }
    
    if ($miCaracterizacionRiesgo->sacrifica > 0) {
        $recomendaciones[] = [
            'area' => 'Manejo Sanitario',
            'texto' => 'Realiza sacrificio en predio. Capacitación en prácticas sanitarias de sacrificio y disposición de residuos.'
        ];
    }
    
    if ($miCaracterizacionRiesgo->tiene_asistencia == 0) {
        $recomendaciones[] = [
            'area' => 'Asistencia Técnica',
            'texto' => 'No recibe asistencia técnica. Contactar con autoridad sanitaria para programa de acompañamiento veterinario.'
        ];
    }
    
    if ($miVisita->fue_visitado == 0) {
        $recomendaciones[] = [
            'area' => 'Vigilancia',
            'texto' => 'No ha sido visitado por autoridad sanitaria. Solicitar inspección y actualización de estado sanitario.'
        ];
    }
    
    // Fortalezas
    if ($miInfoEpidemiologica->tiene_enfermos == 0) {
        $fortalezas[] = 'Sin reportes de enfermedades';
    }
    
    if ($miCaracterizacionRiesgo->tiene_asistencia > 0) {
        $fortalezas[] = 'Cuenta con asistencia técnica veterinaria';
    }
    
    if ($miCaracterizacionRiesgo->colinda_riesgo == 0) {
        $fortalezas[] = 'No colinda con establecimientos de riesgo';
    }
    
    if ($miVisita->fue_visitado > 0) {
        $fortalezas[] = 'Ha sido inspeccionado por autoridad sanitaria';
    }
    
    return view('dashboard.riesgo-epidemiologico-individual', compact(
        'todosPredios',
        'predioId',
        'predio',
        'scoreGeneral',
        'scorePromedioMunicipal',
        'miDiversidad',
        'promedioDiversidad',
        'miTipoExplotacion',
        'miInfoEpidemiologica',
        'promedioEnfermos',
        'miCaracterizacionRiesgo',
        'misFactoresRiesgo',
        'promedioFactoresRiesgo',
        'miVisita',
        'promedioVisitas',
        'recomendaciones',
        'fortalezas'
    ));
}


/**
 * Dashboard Regional - Servicios Ambientales
 */
public function serviciosAmbientalesConsolidado(Request $request)
{
    $municipio = $request->get('municipio', 'all');
    
    // Base query con filtro de municipio
    $queryPredios = Predios::query();
    if ($municipio != 'all') {
        $queryPredios->where('municipio', $municipio);
    }
    
    $prediosIds = $queryPredios->pluck('id');
    $totalPredios = $prediosIds->count();
    
    // Servicios ambientales por tipo
    $serviciosPorTipo = DB::table('servicios_ambientales')
        ->join('tp_servicio_ambient', 'servicios_ambientales.id_tip_servicio', '=', 'tp_servicio_ambient.id')
        ->whereIn('servicios_ambientales.id_predio', $prediosIds)
        ->where(function($query) {
            $query->where('servicios_ambientales.hectareas', '>', 0)
                  ->orWhereNotNull('servicios_ambientales.hectareas');
        })
        ->select(
            'tp_servicio_ambient.id',
            'tp_servicio_ambient.nombre',
            DB::raw('COUNT(DISTINCT servicios_ambientales.id_predio) as predios_con_servicio'),
            DB::raw('SUM(CASE WHEN servicios_ambientales.hectareas > 0 THEN servicios_ambientales.hectareas ELSE 0 END) as hectareas_totales'),
            DB::raw('AVG(CASE WHEN servicios_ambientales.hectareas > 0 THEN servicios_ambientales.hectareas ELSE 0 END) as hectareas_promedio')
        )
        ->groupBy('tp_servicio_ambient.id', 'tp_servicio_ambient.nombre')
        ->orderBy('hectareas_totales', 'desc')
        ->get();
    
    // Total general
    $totalHectareasServicios = $serviciosPorTipo->sum('hectareas_totales');
    $prediosConServicios = DB::table('servicios_ambientales')
        ->whereIn('id_predio', $prediosIds)
        ->where(function($query) {
            $query->where('hectareas', '>', 0)
                  ->orWhereNotNull('hectareas');
        })
        ->distinct('id_predio')
        ->count('id_predio');
    
    // Calcular porcentaje de cobertura
    $porcentajeCobertura = $totalPredios > 0 ? round(($prediosConServicios / $totalPredios) * 100) : 0;
    
    // Top 3 servicios más implementados
    $topServicios = $serviciosPorTipo->take(3);
    
    // Servicios menos implementados
    $serviciosBajos = $serviciosPorTipo->sortBy('predios_con_servicio')->take(3);
    
    $municipiosUnicos = Predios::distinct()->whereNotNull('municipio')->pluck('municipio')->sort();
    
    return view('dashboard.servicios-ambientales-consolidado', compact(
        'totalPredios',
        'serviciosPorTipo',
        'totalHectareasServicios',
        'prediosConServicios',
        'porcentajeCobertura',
        'topServicios',
        'serviciosBajos',
        'municipiosUnicos',
        'municipio'
    ));
}
/**
 * Dashboard Individual - Servicios Ambientales
 */
public function serviciosAmbientalesIndividual(Request $request)
{
    $predioId = $request->get('predio');
    
    // Obtener todos los predios para el selector
    $todosPredios = Predios::select('id', 'nombre_predio', 'municipio')
        ->orderBy('nombre_predio')
        ->get();
    
    if (!$predioId) {
        return view('dashboard.servicios-ambientales-individual', compact('todosPredios', 'predioId'));
    }
    
    $predio = Predios::findOrFail($predioId);
    $municipio = $predio->municipio;
    
    // Predios del mismo municipio para comparar
    $prediosMunicipio = Predios::where('municipio', $municipio)->pluck('id');
    
    // ============================================
    // MIS SERVICIOS AMBIENTALES
    // ============================================
    $misServicios = DB::table('servicios_ambientales')
        ->join('tp_servicio_ambient', 'servicios_ambientales.id_tip_servicio', '=', 'tp_servicio_ambient.id')
        ->where('servicios_ambientales.id_predio', $predioId)
        ->where(function($query) {
            $query->where('servicios_ambientales.hectareas', '>', 0)
                  ->orWhereNotNull('servicios_ambientales.hectareas');
        })
        ->select(
            'tp_servicio_ambient.id',
            'tp_servicio_ambient.nombre',
            'servicios_ambientales.hectareas',
            'servicios_ambientales.materiales_establecidos'
        )
        ->get();
    
    $misHectareasTotales = $misServicios->sum('hectareas');
    $cantidadServicios = $misServicios->count();
    
    // ============================================
    // PROMEDIO MUNICIPAL
    // ============================================
    $promedioMunicipal = DB::table('servicios_ambientales')
        ->whereIn('id_predio', $prediosMunicipio)
        ->where(function($query) {
            $query->where('hectareas', '>', 0)
                  ->orWhereNotNull('hectareas');
        })
        ->selectRaw('
            AVG(hectareas) as promedio_hectareas_por_servicio,
            COUNT(DISTINCT id_predio) as predios_con_servicios
        ')
        ->first();
    
    $promedioHectareasMunicipal = DB::table('servicios_ambientales')
        ->whereIn('id_predio', $prediosMunicipio)
        ->where(function($query) {
            $query->where('hectareas', '>', 0)
                  ->orWhereNotNull('hectareas');
        })
        ->selectRaw('id_predio, SUM(hectareas) as total_predio')
        ->groupBy('id_predio')
        ->get()
        ->avg('total_predio');
    
    $promedioServiciosMunicipal = DB::table('servicios_ambientales')
        ->whereIn('id_predio', $prediosMunicipio)
        ->where(function($query) {
            $query->where('hectareas', '>', 0)
                  ->orWhereNotNull('hectareas');
        })
        ->selectRaw('id_predio, COUNT(DISTINCT id_tip_servicio) as cantidad')
        ->groupBy('id_predio')
        ->get()
        ->avg('cantidad');
    
    // ============================================
    // COMPARACIÓN POR TIPO DE SERVICIO
    // ============================================
    $todosLosServicios = DB::table('tp_servicio_ambient')->get();
    $comparacionServicios = [];
    
    foreach ($todosLosServicios as $tipoServicio) {
        $miServicio = $misServicios->where('id', $tipoServicio->id)->first();
        
        $promedioTipo = DB::table('servicios_ambientales')
            ->whereIn('id_predio', $prediosMunicipio)
            ->where('id_tip_servicio', $tipoServicio->id)
            ->where(function($query) {
                $query->where('hectareas', '>', 0)
                      ->orWhereNotNull('hectareas');
            })
            ->avg('hectareas');
        
        $comparacionServicios[] = [
            'nombre' => $tipoServicio->nombre,
            'mis_hectareas' => $miServicio ? $miServicio->hectareas : 0,
            'promedio_hectareas' => round($promedioTipo ?? 0, 2),
            'tengo_servicio' => $miServicio ? true : false,
            'materiales' => $miServicio ? $miServicio->materiales_establecidos : null,
        ];
    }
    
    // ============================================
    // CALCULAR SCORE DE SOSTENIBILIDAD (0-100)
    // ============================================
    // Score basado en:
    // 1. Cantidad de servicios (40%): más servicios = más sostenible
    // 2. Hectáreas destinadas (40%): más hectáreas = más compromiso
    // 3. Comparación con promedio (20%): estar sobre el promedio suma
    
    $scoreServicios = ($cantidadServicios / 9) * 100; // De 9 servicios posibles
    $scoreHectareas = min(($misHectareasTotales / 50) * 100, 100); // Referencia: 50 ha es excelente
    $scoreComparacion = ($misHectareasTotales >= $promedioHectareasMunicipal) ? 100 : (($misHectareasTotales / $promedioHectareasMunicipal) * 100);
    
    $scoreGeneral = round(($scoreServicios * 0.4) + ($scoreHectareas * 0.4) + ($scoreComparacion * 0.2));
    
    $scorePromedioMunicipal = 50; // Valor base para comparación
    
    // ============================================
    // RECOMENDACIONES Y FORTALEZAS
    // ============================================
    $recomendaciones = [];
    $fortalezas = [];
    
    if ($cantidadServicios == 0) {
        $recomendaciones[] = [
            'area' => 'Implementación Inicial',
            'texto' => 'No tiene servicios ambientales registrados. Comience con bosques de conservación o cercas vivas, que son fáciles de implementar.'
        ];
    } elseif ($cantidadServicios <= 2) {
        $recomendaciones[] = [
            'area' => 'Diversificación',
            'texto' => "Solo tiene {$cantidadServicios} servicios ambientales. Diversificar con más prácticas aumenta la resiliencia del predio."
        ];
    }
    
    if ($misHectareasTotales < $promedioHectareasMunicipal) {
        $diferencia = round($promedioHectareasMunicipal - $misHectareasTotales, 1);
        $recomendaciones[] = [
            'area' => 'Ampliación',
            'texto' => "Está {$diferencia} ha por debajo del promedio municipal. Considere ampliar la cobertura de servicios ambientales."
        ];
    }
    
    // Servicios específicos que NO tiene pero que son comunes
    $serviciosComunes = ['BOSQUE MADURO', 'BOSQUE SECUNDARIO', 'CERCAS VIVAS Y BARRERAS ROMPEVIENTOS (KM)'];
    foreach ($serviciosComunes as $servicioComun) {
        $tieneServicio = $misServicios->contains(function($s) use ($servicioComun) {
            return stripos($s->nombre, $servicioComun) !== false;
        });
        
        if (!$tieneServicio) {
            $nombreCorto = explode(' ', $servicioComun)[0] . ' ' . explode(' ', $servicioComun)[1];
            $recomendaciones[] = [
                'area' => 'Servicio Recomendado',
                'texto' => "Considere implementar {$nombreCorto}. Es una práctica común y efectiva en la región."
            ];
        }
    }
    
    // Fortalezas
    if ($cantidadServicios >= 5) {
        $fortalezas[] = 'Alta diversidad de servicios ambientales';
    }
    
    if ($misHectareasTotales >= $promedioHectareasMunicipal) {
        $fortalezas[] = 'Extensión de servicios por encima del promedio municipal';
    }
    
    if ($cantidadServicios >= 3) {
        $fortalezas[] = 'Compromiso activo con la conservación ambiental';
    }
    
    if ($misHectareasTotales >= 20) {
        $fortalezas[] = 'Significativa área destinada a servicios ambientales';
    }
    
    return view('dashboard.servicios-ambientales-individual', compact(
        'todosPredios',
        'predioId',
        'predio',
        'scoreGeneral',
        'scorePromedioMunicipal',
        'misServicios',
        'misHectareasTotales',
        'cantidadServicios',
        'promedioHectareasMunicipal',
        'promedioServiciosMunicipal',
        'comparacionServicios',
        'recomendaciones',
        'fortalezas'
    ));
}


/**
 * Dashboard de Análisis Comparativo
 */
public function analisisComparativo(Request $request)
{
    $modulo = $request->get('modulo', 'info_predios'); // Default
    
    // Obtener municipios únicos
    $municipios = Predios::distinct()->whereNotNull('municipio')->pluck('municipio')->sort();
    
    $datosComparativos = [];
    
    switch ($modulo) {
        case 'info_predios':
            $datosComparativos = $this->compararInfoPredios($municipios);
            break;
        case 'bgp':
            $datosComparativos = $this->compararBGP($municipios);
            break;
        case 'riesgo':
            $datosComparativos = $this->compararRiesgo($municipios);
            break;
        case 'servicios':
            $datosComparativos = $this->compararServiciosAmbientales($municipios);
            break;
        case 'censo':  // ← NUEVO
            $datosComparativos = $this->compararCenso($municipios);
            break;
    }
    
    return view('dashboard.analisis-comparativo', compact('datosComparativos', 'municipios', 'modulo'));
}

/**
 * Comparar Información de Predios entre municipios
 */
private function compararInfoPredios($municipios)
{
    $comparacion = [];
    
    foreach ($municipios as $municipio) {
        $prediosIds = Predios::where('municipio', $municipio)->pluck('id');
        $totalPredios = $prediosIds->count();
        
        if ($totalPredios == 0) continue;
        
        // ============================================
        // 1. ÁREAS - usando estructura REAL
        // ============================================
        $areaData = DB::table('areas')
            ->join('tipo_areas', 'areas.id_tipo_area', '=', 'tipo_areas.id')
            ->whereIn('areas.id_predio', $prediosIds)
            ->where('areas.tipo_medidas', 'Hectáreas')
            ->select(
                DB::raw('SUM(CASE 
                    WHEN areas.medidas REGEXP "^[0-9 ]+$" 
                    THEN CAST(REPLACE(REPLACE(areas.medidas, " ", "."), ",", ".") AS DECIMAL(10,2))
                    ELSE 0 
                END) as total_hectareas')
            )
            ->first();
        
        $area_total = $areaData->total_hectareas ?? 0;
        
        // ============================================
        // 2. INFO TIERRA Y AGUA - campos REALES
        // ============================================
        $tierraAgua = DB::table('info_tierra_agua')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT CASE WHEN suelos_predominantes IS NOT NULL AND suelos_predominantes != "" THEN id_predio END) as con_info_suelos'),
                DB::raw('COUNT(DISTINCT CASE WHEN drenaje IS NOT NULL AND drenaje != "" THEN id_predio END) as con_drenaje'),
                DB::raw('COUNT(DISTINCT CASE WHEN fuente_calidad_agua IS NOT NULL AND fuente_calidad_agua != "" THEN id_predio END) as con_fuente_agua'),
                DB::raw('COUNT(DISTINCT CASE WHEN manejo_cuencas_nac_agua IS NOT NULL AND manejo_cuencas_nac_agua != "" THEN id_predio END) as maneja_cuencas')
            )
            ->first();
        
        // ============================================
        // 3. PASTOS - campos REALES
        // ============================================
        $pastos = DB::table('man_past_potr_cerc')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT CASE WHEN UPPER(r_fertilazion_potreros) = "SI" THEN id_predio END) as fertiliza_potreros'),
                DB::raw('COUNT(DISTINCT CASE WHEN UPPER(r_control_plagas) = "SI" THEN id_predio END) as controla_plagas'),
                DB::raw('COUNT(DISTINCT CASE WHEN UPPER(r_control_maleza) = "SI" THEN id_predio END) as controla_maleza'),
                DB::raw('COUNT(DISTINCT CASE WHEN UPPER(div_potreros) = "SI" THEN id_predio END) as tiene_division'),
                DB::raw('COUNT(DISTINCT CASE WHEN cercas IS NOT NULL AND cercas != "" THEN id_predio END) as tiene_cercas')
            )
            ->first();
        
        // ============================================
        // 4. GANADO - campos REALES
        // ============================================
        $ganado = DB::table('man_gen_ganado')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT CASE WHEN ident_animales IS NOT NULL AND ident_animales != "" THEN id_predio END) as identifica_animales'),
                DB::raw('COUNT(DISTINCT CASE WHEN control_parasito_extern IS NOT NULL AND control_parasito_extern != "" THEN id_predio END) as control_parasitos'),
                DB::raw('COUNT(DISTINCT CASE WHEN pesaje_animal IS NOT NULL AND pesaje_animal != "" THEN id_predio END) as hace_pesaje'),
                DB::raw('COUNT(DISTINCT CASE WHEN sumin_sal IS NOT NULL AND sumin_sal != "" THEN id_predio END) as suministra_sal')
            )
            ->first();
        
        // ============================================
        // 5. ASPECTOS AMBIENTALES - campos REALES
        // ============================================
        $ambiental = DB::table('inform_aspect_med_ambient')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT CASE WHEN dispos_aguas_servid IS NOT NULL AND dispos_aguas_servid != "" THEN id_predio END) as maneja_aguas'),
                DB::raw('COUNT(DISTINCT CASE WHEN dispos_excrement_bovinos IS NOT NULL AND dispos_excrement_bovinos != "" THEN id_predio END) as maneja_excrementos'),
                DB::raw('COUNT(DISTINCT CASE WHEN manejo_basuras IS NOT NULL AND manejo_basuras != "" THEN id_predio END) as maneja_basuras')
            )
            ->first();
        
        // ============================================
        // 6. GESTIÓN DE INFORMACIÓN - campos REALES
        // ============================================
        $gestion = DB::table('gestion_informacion')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT CASE WHEN los_registros_son IS NOT NULL AND los_registros_son != "" THEN id_predio END) as lleva_registros'),
                DB::raw('COUNT(DISTINCT CASE WHEN calcula_indicadores IS NOT NULL AND calcula_indicadores != "" THEN id_predio END) as calcula_indicadores')
            )
            ->first();
        
        $comparacion[] = [
            'municipio' => $municipio,
            'total_predios' => $totalPredios,
            'area_total' => round($area_total, 2),
            'porcentaje_info_suelos' => $totalPredios > 0 ? round(($tierraAgua->con_info_suelos / $totalPredios) * 100) : 0,
            'porcentaje_drenaje' => $totalPredios > 0 ? round(($tierraAgua->con_drenaje / $totalPredios) * 100) : 0,
            'porcentaje_fuente_agua' => $totalPredios > 0 ? round(($tierraAgua->con_fuente_agua / $totalPredios) * 100) : 0,
            'porcentaje_fertiliza' => $totalPredios > 0 ? round(($pastos->fertiliza_potreros / $totalPredios) * 100) : 0,
            'porcentaje_control_plagas' => $totalPredios > 0 ? round(($pastos->controla_plagas / $totalPredios) * 100) : 0,
            'porcentaje_division_potreros' => $totalPredios > 0 ? round(($pastos->tiene_division / $totalPredios) * 100) : 0,
            'porcentaje_identifica' => $totalPredios > 0 ? round(($ganado->identifica_animales / $totalPredios) * 100) : 0,
            'porcentaje_control_parasitos' => $totalPredios > 0 ? round(($ganado->control_parasitos / $totalPredios) * 100) : 0,
            'porcentaje_maneja_aguas' => $totalPredios > 0 ? round(($ambiental->maneja_aguas / $totalPredios) * 100) : 0,
            'porcentaje_lleva_registros' => $totalPredios > 0 ? round(($gestion->lleva_registros / $totalPredios) * 100) : 0,
        ];
    }
    
    return collect($comparacion);
}

/**
 * Comparar BPG entre municipios
 */
private function compararBGP($municipios)
{
    $comparacion = [];
    
    // Definir las 8 secciones de BPG
    $secciones = [
        ['nombre' => 'Sanidad Animal', 'inicio' => 1, 'fin' => 5],
        ['nombre' => 'Identificación', 'inicio' => 6, 'fin' => 7],
        ['nombre' => 'Bioseguridad', 'inicio' => 8, 'fin' => 12],
        ['nombre' => 'BPMV - Medicamentos', 'inicio' => 13, 'fin' => 24],
        ['nombre' => 'BPAA - Alimentación', 'inicio' => 25, 'fin' => 31],
        ['nombre' => 'Saneamiento', 'inicio' => 32, 'fin' => 38],
        ['nombre' => 'Bienestar Animal', 'inicio' => 39, 'fin' => 47],
        ['nombre' => 'Personal', 'inicio' => 48, 'fin' => 100],
    ];
    
    foreach ($municipios as $municipio) {
        $prediosIds = Predios::where('municipio', $municipio)->pluck('id');
        $totalPredios = $prediosIds->count();
        
        if ($totalPredios == 0) continue;
        
        // Calcular score general BPG
        $bgpData = DB::table('informacion_bgp')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('SUM(CASE WHEN UPPER(estado) = "SI" THEN 1 ELSE 0 END) as total_si'),
                DB::raw('SUM(CASE WHEN UPPER(estado) = "NO" THEN 1 ELSE 0 END) as total_no'),
                DB::raw('SUM(CASE WHEN UPPER(estado) = "NA" THEN 1 ELSE 0 END) as total_na'),
                DB::raw('COUNT(DISTINCT id_predio) as predios_con_bgp')
            )
            ->first();
        
        $total_validas = ($bgpData->total_si ?? 0) + ($bgpData->total_no ?? 0);
        $score_bgp = $total_validas > 0 ? round((($bgpData->total_si ?? 0) / $total_validas) * 100) : 0;
        
        // Calcular scores por sección
        $scoresPorSeccion = [];
        foreach ($secciones as $seccion) {
            $seccionData = DB::table('informacion_bgp')
                ->whereIn('id_predio', $prediosIds)
                ->whereBetween('id_tipos_bgp', [$seccion['inicio'], $seccion['fin']])
                ->select(
                    DB::raw('SUM(CASE WHEN UPPER(estado) = "SI" THEN 1 ELSE 0 END) as si'),
                    DB::raw('SUM(CASE WHEN UPPER(estado) = "NO" THEN 1 ELSE 0 END) as no')
                )
                ->first();
            
            $total = ($seccionData->si ?? 0) + ($seccionData->no ?? 0);
            $scoresPorSeccion[$seccion['nombre']] = $total > 0 ? round((($seccionData->si ?? 0) / $total) * 100) : 0;
        }
        
        $comparacion[] = [
            'municipio' => $municipio,
            'total_predios' => $totalPredios,
            'predios_con_bgp' => $bgpData->predios_con_bgp ?? 0,
            'score_bgp' => $score_bgp,
            'practicas_cumplidas' => $bgpData->total_si ?? 0,
            'practicas_no_cumplidas' => $bgpData->total_no ?? 0,
            'practicas_na' => $bgpData->total_na ?? 0,
            'cobertura_bgp' => $totalPredios > 0 ? round((($bgpData->predios_con_bgp ?? 0) / $totalPredios) * 100) : 0,
            'scores_secciones' => $scoresPorSeccion,
        ];
    }
    
    return collect($comparacion);
}

/**
 * Comparar Riesgo Epidemiológico entre municipios
 */
private function compararRiesgo($municipios)
{
    $comparacion = [];
    
    foreach ($municipios as $municipio) {
        $prediosIds = Predios::where('municipio', $municipio)->pluck('id');
        $totalPredios = $prediosIds->count();
        
        if ($totalPredios == 0) continue;
        
        // ============================================
        // 1. TIPOS DE EXPLOTACIÓN - campos REALES
        // ============================================
        $tiposExplotacion = DB::table('tp_explotacion')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT CASE WHEN bovinos IS NOT NULL AND bovinos != "" THEN id_predio END) as con_bovinos'),
                DB::raw('COUNT(DISTINCT CASE WHEN bufalinos IS NOT NULL AND bufalinos != "" THEN id_predio END) as con_bufalinos'),
                DB::raw('COUNT(DISTINCT CASE WHEN porcinos IS NOT NULL AND porcinos != "" THEN id_predio END) as con_porcinos'),
                DB::raw('COUNT(DISTINCT CASE WHEN equinos IS NOT NULL AND equinos != "" THEN id_predio END) as con_equinos'),
                DB::raw('COUNT(DISTINCT CASE WHEN ovinos IS NOT NULL AND ovinos != "" THEN id_predio END) as con_ovinos'),
                DB::raw('COUNT(DISTINCT CASE WHEN caprinos IS NOT NULL AND caprinos != "" THEN id_predio END) as con_caprinos'),
                DB::raw('COUNT(DISTINCT CASE WHEN aves_corral IS NOT NULL AND aves_corral != "" THEN id_predio END) as con_aves'),
                DB::raw('COUNT(DISTINCT CASE WHEN peces IS NOT NULL AND peces != "" THEN id_predio END) as con_peces'),
                DB::raw('COUNT(DISTINCT CASE WHEN apicolas IS NOT NULL AND apicolas != "" THEN id_predio END) as con_abejas')
            )
            ->first();
        
        // ============================================
        // 2. INFORMACIÓN EPIDEMIOLÓGICA - campos REALES
        // ============================================
        $infoEpi = DB::table('infor_epidemiologica')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT CASE WHEN UPPER(anim_enferm_control) = "SI" THEN id_predio END) as predios_con_enfermos'),
                DB::raw('SUM(CASE WHEN anim_enferm_control_cant > 0 THEN anim_enferm_control_cant ELSE 0 END) as total_animales_enfermos'),
                DB::raw('COUNT(DISTINCT CASE WHEN UPPER(toma_muestra) = "SI" THEN id_predio END) as con_muestras'),
                DB::raw('SUM(CASE WHEN toma_muestra_numeros > 0 THEN toma_muestra_numeros ELSE 0 END) as total_muestras')
            )
            ->first();
        
        // ============================================
        // 3. CARACTERIZACIÓN DE RIESGO - campos REALES
        // ============================================
        $riesgo = DB::table('caracterizacion_riesgo')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT CASE WHEN UPPER(colinda_establecim_riesgo) = "SI" THEN id_predio END) as colinda_riesgo'),
                DB::raw('COUNT(DISTINCT CASE WHEN UPPER(sacrif_anim_pred) = "SI" THEN id_predio END) as sacrifica_animales'),
                DB::raw('COUNT(DISTINCT CASE WHEN UPPER(trabajan_otr_explotacion) = "SI" THEN id_predio END) as trabajadores_otras'),
                DB::raw('COUNT(DISTINCT CASE WHEN UPPER(asistencia_tecnica) = "SI" THEN id_predio END) as con_asistencia'),
                DB::raw('AVG(CASE WHEN num_trabajadores > 0 THEN num_trabajadores ELSE 0 END) as promedio_trabajadores')
            )
            ->first();
        
        // ============================================
        // 4. VISITAS - campos REALES
        // ============================================
        $visitas = DB::table('visita_predios_riesgo')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT id_predio) as predios_visitados'),
                DB::raw('COUNT(DISTINCT CASE WHEN UPPER(toma_muestras) = "SI" THEN id_predio END) as visitas_con_muestras'),
                DB::raw('SUM(CASE WHEN num_muestras > 0 THEN num_muestras ELSE 0 END) as total_muestras_visita')
            )
            ->first();
        
        // Calcular índice de riesgo (0-100)
        $factores_riesgo = ($riesgo->colinda_riesgo ?? 0) + 
                          ($riesgo->sacrifica_animales ?? 0) + 
                          ($riesgo->trabajadores_otras ?? 0) + 
                          ($totalPredios - ($riesgo->con_asistencia ?? 0));
        $indice_riesgo = $totalPredios > 0 ? round(($factores_riesgo / ($totalPredios * 4)) * 100) : 0;
        
        // Tasa de diagnóstico
        $tasa_diagnostico = ($infoEpi->predios_con_enfermos ?? 0) > 0 
            ? round((($infoEpi->con_muestras ?? 0) / $infoEpi->predios_con_enfermos) * 100) 
            : 0;
        
        $comparacion[] = [
            'municipio' => $municipio,
            'total_predios' => $totalPredios,
            'predios_con_enfermos' => $infoEpi->predios_con_enfermos ?? 0,
            'total_animales_enfermos' => $infoEpi->total_animales_enfermos ?? 0,
            'predios_con_muestras' => $infoEpi->con_muestras ?? 0,
            'total_muestras' => $infoEpi->total_muestras ?? 0,
            'tasa_diagnostico' => $tasa_diagnostico,
            'indice_riesgo' => $indice_riesgo,
            'porcentaje_con_asistencia' => $totalPredios > 0 ? round((($riesgo->con_asistencia ?? 0) / $totalPredios) * 100) : 0,
            'predios_visitados' => $visitas->predios_visitados ?? 0,
            'cobertura_vigilancia' => $totalPredios > 0 ? round((($visitas->predios_visitados ?? 0) / $totalPredios) * 100) : 0,
            'predios_con_bovinos' => $tiposExplotacion->con_bovinos ?? 0,
            'predios_con_porcinos' => $tiposExplotacion->con_porcinos ?? 0,
            'promedio_trabajadores' => round($riesgo->promedio_trabajadores ?? 0, 1),
        ];
    }
    
    return collect($comparacion);
}

/**
 * Comparar Servicios Ambientales entre municipios
 */
private function compararServiciosAmbientales($municipios)
{
    $comparacion = [];
    
    foreach ($municipios as $municipio) {
        $prediosIds = Predios::where('municipio', $municipio)->pluck('id');
        $totalPredios = $prediosIds->count();
        
        if ($totalPredios == 0) continue;
        
        // ============================================
        // SERVICIOS AMBIENTALES - campos REALES
        // ============================================
        $servicios = DB::table('servicios_ambientales')
            ->whereIn('id_predio', $prediosIds)
            ->where(function($query) {
                $query->where('hectareas', '>', 0)
                      ->orWhereNotNull('hectareas');
            })
            ->select(
                DB::raw('COUNT(DISTINCT id_predio) as predios_con_servicios'),
                DB::raw('SUM(CASE WHEN hectareas > 0 THEN hectareas ELSE 0 END) as hectareas_totales'),
                DB::raw('COUNT(DISTINCT id_tip_servicio) as tipos_servicios')
            )
            ->first();
        
        // Servicio principal (más hectáreas)
        $servicioPrincipal = DB::table('servicios_ambientales')
            ->join('tp_servicio_ambient', 'servicios_ambientales.id_tip_servicio', '=', 'tp_servicio_ambient.id')
            ->whereIn('servicios_ambientales.id_predio', $prediosIds)
            ->where(function($query) {
                $query->where('servicios_ambientales.hectareas', '>', 0)
                      ->orWhereNotNull('servicios_ambientales.hectareas');
            })
            ->select(
                'tp_servicio_ambient.nombre',
                DB::raw('SUM(CASE WHEN servicios_ambientales.hectareas > 0 THEN servicios_ambientales.hectareas ELSE 0 END) as hectareas'),
                DB::raw('COUNT(DISTINCT servicios_ambientales.id_predio) as predios')
            )
            ->groupBy('tp_servicio_ambient.nombre')
            ->orderByDesc('hectareas')
            ->first();
        
        $comparacion[] = [
            'municipio' => $municipio,
            'total_predios' => $totalPredios,
            'predios_con_servicios' => $servicios->predios_con_servicios ?? 0,
            'hectareas_totales' => round($servicios->hectareas_totales ?? 0, 2),
            'tipos_servicios' => $servicios->tipos_servicios ?? 0,
            'cobertura_servicios' => $totalPredios > 0 ? round((($servicios->predios_con_servicios ?? 0) / $totalPredios) * 100) : 0,
            'hectareas_promedio_predio' => ($servicios->predios_con_servicios ?? 0) > 0 
                ? round(($servicios->hectareas_totales ?? 0) / $servicios->predios_con_servicios, 2) 
                : 0,
            'servicio_principal' => $servicioPrincipal->nombre ?? 'N/A',
            'hectareas_principal' => round($servicioPrincipal->hectareas ?? 0, 2),
        ];
    }
    
    return collect($comparacion);
}
/**
 * Comparar Censo Animal entre municipios
 */
private function compararCenso($municipios)
{
    $comparacion = [];
    
    foreach ($municipios as $municipio) {
        $prediosIds = Predios::where('municipio', $municipio)->pluck('id');
        $totalPredios = $prediosIds->count();
        
        if ($totalPredios == 0) continue;
        
        // ============================================
        // BOVINOS
        // ============================================
        $bovinos = DB::table('censo_bovinos')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT id_predio) as predios_con_bovinos'),
                DB::raw('SUM(COALESCE(total_bovinos, 0)) as total_bovinos'),
                DB::raw('SUM(COALESCE(total_hembras, 0)) as total_hembras_bovinas'),
                DB::raw('SUM(COALESCE(total_machos, 0)) as total_machos_bovinos')
            )
            ->first();
        
        // ============================================
        // BUFALINOS
        // ============================================
        $bufalinos = DB::table('censo_bufalinos')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT id_predio) as predios_con_bufalinos'),
                DB::raw('SUM(COALESCE(total_bufalinos, 0)) as total_bufalinos')
            )
            ->first();
        
        // ============================================
        // PORCINOS
        // ============================================
        $porcinos = DB::table('censo_porcinos')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT id_predio) as predios_con_porcinos'),
                DB::raw('SUM(COALESCE(total_porcinos, 0)) as total_porcinos')
            )
            ->first();
        
        // ============================================
        // ÉQUIDOS
        // ============================================
        $equidos = DB::table('censo_equidos')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT id_predio) as predios_con_equidos'),
                DB::raw('SUM(COALESCE(total_equidos, 0)) as total_equidos')
            )
            ->first();
        
        // ============================================
        // OVINOS Y CAPRINOS
        // ============================================
        $ovinoCaprino = DB::table('censo_ovino_caprino')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT id_predio) as predios_con_ovino_caprino'),
                DB::raw('SUM(COALESCE(total_ovinos, 0)) as total_ovinos'),
                DB::raw('SUM(COALESCE(total_caprinos, 0)) as total_caprinos')
            )
            ->first();
        
        // ============================================
        // AVES COMERCIALES
        // ============================================
        $avesComerciales = DB::table('censo_aves_comerciales')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT id_predio) as predios_con_aves_comerciales'),
                DB::raw('SUM(COALESCE(num_aves, 0)) as total_aves_comerciales')
            )
            ->first();
        
        // ============================================
        // AVES TRASPATIO
        // ============================================
        $avesTraspatio = DB::table('censo_aves_traspatio')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT id_predio) as predios_con_aves_traspatio'),
                DB::raw('SUM(COALESCE(num_aves, 0)) as total_aves_traspatio')
            )
            ->first();
        
        // ============================================
        // PECES
        // ============================================
        $peces = DB::table('censo_peces')
            ->whereIn('id_predio', $prediosIds)
            ->select(
                DB::raw('COUNT(DISTINCT id_predio) as predios_con_peces'),
                DB::raw('SUM(
                    CASE 
                        WHEN COALESCE(total_peces, 0) > 0 THEN total_peces
                        ELSE (COALESCE(ovas, 0) + COALESCE(alevinos, 0) + COALESCE(engorde, 0) + COALESCE(reproductores, 0))
                    END
                ) as total_peces')
            )
            ->first();
        
        // ============================================
        // TOTALES
        // ============================================
        $totalAnimales = ($bovinos->total_bovinos ?? 0) +
                        ($bufalinos->total_bufalinos ?? 0) +
                        ($porcinos->total_porcinos ?? 0) +
                        ($equidos->total_equidos ?? 0) +
                        ($ovinoCaprino->total_ovinos ?? 0) +
                        ($ovinoCaprino->total_caprinos ?? 0) +
                        ($avesComerciales->total_aves_comerciales ?? 0) +
                        ($avesTraspatio->total_aves_traspatio ?? 0) +
                        ($peces->total_peces ?? 0);
        
        // Animales por predio promedio
        $animalesPorPredio = $totalPredios > 0 ? round($totalAnimales / $totalPredios, 1) : 0;
        
        // Especie dominante
        $especies = [
            'Bovinos' => $bovinos->total_bovinos ?? 0,
            'Bufalinos' => $bufalinos->total_bufalinos ?? 0,
            'Porcinos' => $porcinos->total_porcinos ?? 0,
            'Équidos' => $equidos->total_equidos ?? 0,
            'Ovinos/Caprinos' => ($ovinoCaprino->total_ovinos ?? 0) + ($ovinoCaprino->total_caprinos ?? 0),
            'Aves' => ($avesComerciales->total_aves_comerciales ?? 0) + ($avesTraspatio->total_aves_traspatio ?? 0),
            'Peces' => $peces->total_peces ?? 0,
        ];
        
        arsort($especies);
        $especieDominante = array_key_first($especies);
        
        $comparacion[] = [
            'municipio' => $municipio,
            'total_predios' => $totalPredios,
            'total_animales' => $totalAnimales,
            'animales_por_predio' => $animalesPorPredio,
            'especie_dominante' => $especieDominante,
            'total_bovinos' => $bovinos->total_bovinos ?? 0,
            'predios_bovinos' => $bovinos->predios_con_bovinos ?? 0,
            'total_bufalinos' => $bufalinos->total_bufalinos ?? 0,
            'total_porcinos' => $porcinos->total_porcinos ?? 0,
            'predios_porcinos' => $porcinos->predios_con_porcinos ?? 0,
            'total_equidos' => $equidos->total_equidos ?? 0,
            'total_ovinos' => $ovinoCaprino->total_ovinos ?? 0,
            'total_caprinos' => $ovinoCaprino->total_caprinos ?? 0,
            'total_aves' => ($avesComerciales->total_aves_comerciales ?? 0) + ($avesTraspatio->total_aves_traspatio ?? 0),
            'total_peces' => $peces->total_peces ?? 0,
        ];
    }
    
    return collect($comparacion);
}
}