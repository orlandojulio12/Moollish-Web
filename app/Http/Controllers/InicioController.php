<?php

namespace App\Http\Controllers;

use App\Exports\DashboardExport;
use App\Exports\DashboardExports;
use App\Models\Animal;
use App\Models\Areas;
use App\Models\CarazterizacionRiesgo;
use App\Models\InfoTierraAgua;
use App\Models\Lote;
use App\Models\ManGenGanado;
use App\Models\ManPastPotrCer;
use App\Models\Potrero;
use App\Models\Predios;
use App\Models\User;
use App\Models\UserMembership;
use App\Models\VisitaPrediosRiesgo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InicioController extends Controller
{
    //
    public function inicio()
    {
        $user = Auth::user();
        // Obtener predios asignados según rol
        if ($user->role->name === 'admin') {
            $predios = Predios::all();
        } else {
            $predios = $user->predios;
        }
        // Contar predios asignados
        $predioCount = $predios->count();
        // Contar animales asociados a los predios del usuario
        $animalCount = Animal::whereIn('id_predio', $predios->pluck('id')->toArray())->count();
        // Contar lotes y potreros asociados a los predios
        $loteCount = Lote::whereIn('predio_id', $predios->pluck('id')->toArray())->count();
        $potreroCount = Potrero::whereIn('predio_id', $predios->pluck('id')->toArray())->count();
        // Consideramos que la configuración está completa si cada categoría tiene al menos un registro
        $configurado = $predioCount > 0 && $animalCount > 0 && ($loteCount > 0 || $potreroCount > 0);

        return view('inicio.inicio', compact(
            'user',
            'predios',
            'configurado',
            'predioCount',
            'animalCount',
            'loteCount',
            'potreroCount',
            //'departamentos',
            'predios'
        ));
    }

    public function caracterizacion()
    {
        $user = Auth::user();

        // Obtener predios asignados según rol
        if ($user->role->name === 'admin') {
            $predios = Predios::all();
        } else {
            $predios = $user->predios; // Se asume que el usuario tiene esta relación
        }
        return view('inicio.caracterizacion', compact('user', 'predios'));
    }

    public function economia()
    {
        $user = Auth::user();

        // Obtener predios asignados según rol
        if ($user->role->name === 'admin') {
            $predios = Predios::all();
        } else {
            $predios = $user->predios; // Se asume que el usuario tiene esta relación
        }

        return view('inicio.economia', compact('user', 'predios'));
    }

    public function registros()
    {
        $user = Auth::user();

        // Obtener predios asignados según rol
        if ($user->role->name === 'admin') {
            $predios = Predios::all();
        } else {
            $predios = $user->predios; // Se asume que el usuario tiene esta relación
        }

        return view('inicio.registros', compact('user', 'predios'));
    }

    public function listados()
    {
        $user = Auth::user();

        // Obtener predios asignados según rol
        if ($user->role->name === 'admin') {
            $predios = Predios::all();
        } else {
            $predios = $user->predios; // Se asume que el usuario tiene esta relación
        }

        return view('inicio.listados', compact('user', 'predios'));
    }

    public function animales()
    {
        $user = Auth::user();

        // Obtener predios asignados según rol
        if ($user->role->name === 'admin') {
            $predios = Predios::all();
        } else {
            $predios = $user->predios; // Se asume que el usuario tiene esta relación
        }

        return view('inicio.animales', compact('user', 'predios'));
    }


    public function reproduccionAnimal()
    {
        $user = Auth::user();

        // Obtener predios asignados según rol
        if ($user->role->name === 'admin') {
            $predios = Predios::all();
        } else {
            $predios = $user->predios; // Se asume que el usuario tiene esta relación
        }

        return view('inicio.reproduccionAnimal', compact('user', 'predios'));
    }

    public function produccionAnimal()
    {
        $user = Auth::user();

        // Obtener predios asignados según rol
        if ($user->role->name === 'admin') {
            $predios = Predios::all();
        } else {
            $predios = $user->predios; // Se asume que el usuario tiene esta relación
        }

        return view('inicio.produccionAnimal', compact('user', 'predios'));
    }

    public function dashboardUser()
    {
        $user = Auth::user();
        return view('inicio.dashboard', compact('user'));
    }

    public function dashboardAdmin(Request $request)
    {
        $user = Auth::user();

        // Obtener estadísticas principales
        $usuariosActivos = User::count();
        $prediosCount = Predios::count();
        $animalesCount = Animal::count();

        // Obtener solo suscripciones pendientes
        $suscripciones = UserMembership::with(['user', 'membershipPlan'])
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->get();

        // Total de suscriptores (todos excepto rechazados)
        $totalSuscriptores = UserMembership::where('estado', '!=', 'rechazado')->count();

        // Nuevos suscriptores del mes actual (basado en fecha_inicio)
        $nuevosSuscriptores = UserMembership::where('estado', 'activo')
            ->whereYear('fecha_inicio', now()->year)
            ->whereMonth('fecha_inicio', now()->month)
            ->count();

        // Total recaudado (incluye activas y expiradas, excluye gratuitas y rechazadas)
        $totalRecaudado = UserMembership::join('membership_plans', 'user_memberships.membership_plan_id', '=', 'membership_plans.id')
            ->where('user_memberships.estado', '!=', 'rechazado')
            ->where('user_memberships.es_free_trial', false)
            ->sum('membership_plans.precio');

        // Recaudado en el último mes (incluye activas y expiradas, basado en created_at)
        $recaudadoUltimoMes = UserMembership::join('membership_plans', 'user_memberships.membership_plan_id', '=', 'membership_plans.id')
            ->where('user_memberships.estado', '!=', 'rechazado')
            ->where('user_memberships.es_free_trial', false)
            ->whereDate('user_memberships.created_at', '>=', now()->subMonth())
            ->sum('membership_plans.precio');

        // Datos para el gráfico de suscripciones por mes (últimos 9 meses, basado en fecha_inicio)
        $suscripcionesPorMes = [];
        for ($i = 8; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $suscripcionesPorMes[$mes->format('M')] = UserMembership::where('estado', 'activo')
                ->whereYear('fecha_inicio', $mes->year)
                ->whereMonth('fecha_inicio', $mes->month)
                ->count();
        }

        // Recaudación detallada solo de los últimos 3 meses
        $recaudacionDetallada = [];

        // Configurar el idioma español para Carbon globalmente
        \Carbon\Carbon::setLocale('es');

        // Debug - Ver la fecha actual del sistema
        $fechaActual = now();
        \Illuminate\Support\Facades\Log::info('Fecha actual del sistema: ' . $fechaActual->format('Y-m-d H:i:s'));

        // Array con los últimos 3 meses correctamente calculados usando subMonthNoOverflow
        $mesesParaAnalizar = [
            now()->startOfMonth(),                         // Mes actual
            now()->subMonthNoOverflow(1)->startOfMonth(),  // Mes pasado
            now()->subMonthsNoOverflow(2)->startOfMonth()  // Hace 2 meses
        ];

        // Debug - Ver las fechas generadas
        foreach ($mesesParaAnalizar as $i => $fecha) {
            \Illuminate\Support\Facades\Log::info("Mes $i: " . $fecha->format('Y-m-d'));
        }

        // Mapeo de meses en inglés a español
        $mesEspanol = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre'
        ];

        foreach ($mesesParaAnalizar as $mes) {
            $recaudoMes = UserMembership::join('membership_plans', 'user_memberships.membership_plan_id', '=', 'membership_plans.id')
                ->join('users', 'user_memberships.user_id', '=', 'users.id')
                ->select(
                    'users.name as usuario',
                    'users.email',
                    'membership_plans.nombre as plan',
                    'membership_plans.precio',
                    'user_memberships.created_at',
                    'user_memberships.es_free_trial',
                    'user_memberships.estado' // Incluir estado para referencia si es necesario
                )
                ->where('user_memberships.estado', '!=', 'rechazado')
                ->where('user_memberships.es_free_trial', false)
                ->whereYear('user_memberships.created_at', $mes->year)
                ->whereMonth('user_memberships.created_at', $mes->month)
                ->get();

            $totalRecaudoMes = $recaudoMes->sum('precio');

            // Construir el nombre del mes en español manualmente
            $nombreMes = $mesEspanol[$mes->format('F')] . ' ' . $mes->format('Y');

            // Debug - Ver el nombre del mes generado
            \Illuminate\Support\Facades\Log::info("Nombre mes: " . $nombreMes);

            $recaudacionDetallada[] = [
                'mes' => $nombreMes,
                'total' => $totalRecaudoMes,
                'suscripciones' => $recaudoMes
            ];
        }

        $departamentos = Predios::pluck('departamento')->unique()->values();
        $predios = Predios::select('id', 'nombre_predio')->get();

        $idPredio = $request->input('id_predio');

        $query = ManGenGanado::query();
        if ($idPredio) {
            $query->where('id_predio', $idPredio);
        }

        $datos = collect([
            'Sistema de Levante' => $query->clone()->select('sistem_levant_animal as categoria')->selectRaw('COUNT(*) as cantidad')->groupBy('sistem_levant_animal')->pluck('cantidad', 'categoria'),
            'Sistema de Alimentación' => $query->clone()->select('aliment_ternero as categoria')->selectRaw('COUNT(*) as cantidad')->groupBy('aliment_ternero')->pluck('cantidad', 'categoria'),
            'Suplementación Mineral' => $query->clone()->select('sumin_sal as categoria')->selectRaw('COUNT(*) as cantidad')->groupBy('sumin_sal')->pluck('cantidad', 'categoria'),
            'Sistema de Reproducción' => $query->clone()->select('sistem_servic_reproduct as categoria')->selectRaw('COUNT(*) as cantidad')->groupBy('sistem_servic_reproduct')->pluck('cantidad', 'categoria'),
            'Manejo Reproductivo' => $query->clone()->select('form_program_servicios as categoria')->selectRaw('COUNT(*) as cantidad')->groupBy('form_program_servicios')->pluck('cantidad', 'categoria'),
        ]);

        $filtro = json_decode($request->input('filtroPredios'), true);
        $tipo = $filtro['tipo'] ?? null;

        $query = InfoTierraAgua::query();

        if ($tipo === 'predio') {
            $query->where('id_predio', $filtro['id_predio']);
        } elseif ($tipo === 'regional') {
            $query->join('predios', 'info_tierra_agua.id_predio', '=', 'predios.id')
                ->where('predios.departamento', $filtro['departamento']);
        }

        $dataInfoTierraAgua = [
            'fuentes_agua' => (clone $query)->selectRaw("
                    CASE 
                        WHEN fuente_calidad_agua LIKE '%Rio%' THEN 'Río'
                        WHEN fuente_calidad_agua LIKE '%Pozo%' THEN 'Pozo'
                        WHEN fuente_calidad_agua LIKE '%Quebrada%' THEN 'Quebrada'
                        WHEN fuente_calidad_agua LIKE '%Jagüey%' THEN 'Jagüey'
                        WHEN fuente_calidad_agua LIKE '%Nacimiento%' THEN 'Nacimiento'
                        ELSE 'Otros'
                    END AS fuente_calidad_agua,
                    COUNT(*) AS cantidad_predios
                ")
                ->groupBy('fuente_calidad_agua')
                ->get(),

            'tipo_suelo' => (clone $query)->selectRaw("IFNULL(suelos_predominantes, 'Otro') AS tipo_suelo, COUNT(*) AS cantidad_predios")
                ->groupBy('suelos_predominantes')
                ->get(),

            'drenaje' => (clone $query)->selectRaw("IFNULL(drenaje, 'Otro') AS drenaje, COUNT(*) AS cantidad_predios")
                ->groupBy('drenaje')
                ->get(),

            'manejo_cuencas' => (clone $query)->selectRaw("IFNULL(manejo_cuencas_nac_agua, 'Otro') AS manejo_cuencas_nac_agua, COUNT(*) AS cantidad_predios")
                ->groupBy('manejo_cuencas_nac_agua')
                ->get(),
        ];

        $tipo = $request->input('tipo');
        $departamento = $request->input('departamento');
        $id_predio = $request->input('id_predio');

        $totalMedidas = Areas::sum('medidas');

        $datosAreas = Areas::selectRaw("
        tipo_areas.nombre_area AS tipo_area,
        COUNT(areas.id) AS cantidad_predios,
        SUM(areas.medidas) AS area_total,
        ROUND((SUM(areas.medidas) * 100.0 / ?), 2) AS porcentaje
        ", [$totalMedidas])
            ->join('tipo_areas', 'areas.id_tipo_area', '=', 'tipo_areas.id')
            ->groupBy('tipo_areas.nombre_area')
            ->orderByDesc('area_total')
            ->get();


        $pastosCategorias = [
            'Renovación de Pastos' => 'div_potreros_como',
            'División de Potreros' => 'div_potreros',
            'Tipo de Cerca' => 'cercas',
            'Rotación de Potreros' => 'tipo_pastoreo',
        ];

        $valoresPastos = collect();

        $valoresPastos = [];

        foreach ($pastosCategorias as $nombreCategoria => $campo) {
            $conteo = ManPastPotrCer::select($campo, DB::raw('COUNT(*) as cantidad_predios'))
                ->where($campo, '!=', '')
                ->groupBy($campo)
                ->orderByDesc('cantidad_predios')
                ->get();

            $valoresPastos[$nombreCategoria] = $conteo->pluck('cantidad_predios', $campo)->toArray();
        }


        $valoresMA = collect();

        $categoriasMA = [
            'Manejo de Basuras' => 'manejo_basuras',
            'Disposición de Aguas Servidas' => 'dispos_aguas_servid',
            'Disposición de Excremento de Bovinos' => 'dispos_excrement_bovinos',
            'Manejo de Empaques de Productos Químicos' => 'manejo_empaq_produc_quimic',
        ];

        foreach ($categoriasMA as $nombreCategoria => $campo) {
            $resultados = DB::table('inform_aspect_med_ambient')
                ->select($campo, DB::raw('COUNT(*) as cantidad_predios'))
                ->groupBy($campo)
                ->orderByDesc('cantidad_predios')
                ->get();

            $valoresMA[$nombreCategoria] = $resultados->pluck('cantidad_predios', $campo)->toArray();
        }


        $valoresGI = collect();

        // LLEVA REGISTROS
        $llevaRegistros = DB::table('gestion_informacion')
            ->select('donde_regis_info_finca as lleva_registros', DB::raw('COUNT(*) as cantidad_predios'))
            ->groupBy('donde_regis_info_finca')
            ->get();

        $valoresGI['Lleva Registros'] = $llevaRegistros->map(function ($item) {
            return [
                'categoria' => $item->lleva_registros,
                'cantidad' => $item->cantidad_predios
            ];
        });

        // TIPO DE REGISTROS
        $tipoRegistros = DB::table('gestion_informacion')
            ->select('los_registros_son as tipo_registros', DB::raw('COUNT(*) as cantidad_predios'))
            ->whereNotNull('los_registros_son')
            ->groupBy('los_registros_son')
            ->get();

        $valoresGI['Tipo de Registros'] = $tipoRegistros->map(function ($item) {
            return [
                'categoria' => $item->tipo_registros,
                'cantidad' => $item->cantidad_predios
            ];
        });

        // FRECUENCIA DE REGISTROS
        $frecuenciaRegistros = DB::table('gestion_informacion')
            ->select('calcula_indicadores_de as frecuencia_registros', DB::raw('COUNT(*) as cantidad_predios'))
            ->whereNotNull('calcula_indicadores_de')
            ->groupBy('calcula_indicadores_de')
            ->get();

        $valoresGI['Frecuencia de Registros'] = $frecuenciaRegistros->map(function ($item) {
            return [
                'categoria' => $item->frecuencia_registros,
                'cantidad' => $item->cantidad_predios
            ];
        });

        // HERRAMIENTAS INFORMÁTICAS
        $herramientasInformaticas = DB::table('gestion_informacion')
            ->select('utiliza_software_monitore as herramientas_informaticas', DB::raw('COUNT(*) as cantidad_predios'))
            ->groupBy('utiliza_software_monitore')
            ->get();

        $valoresGI['Herramientas Informáticas'] = $herramientasInformaticas->map(function ($item) {
            return [
                'categoria' => $item->herramientas_informaticas,
                'cantidad' => $item->cantidad_predios
            ];
        });

        $especies = [
            'Bovinos'   => 'bovinos',
            'Bufalinos' => 'bufalinos',
            'Porcinos'  => 'porcinos',
            'Equinos'   => 'equinos',
            'Ovinos'    => 'ovinos',
            'Caprinos'  => 'caprinos',
        ];

        $valoresExplotacion = collect();

        foreach ($especies as $nombre => $campo) {
            $cantidad = DB::table('tp_explotacion')
                ->whereNotNull($campo)
                ->where($campo, '!=', '')
                ->count();

            $valoresExplotacion[$nombre] = $cantidad;
        }

        $diversidad = DB::table('tp_explotacion')->selectRaw("
        CASE
            WHEN 
                (bovinos IS NOT NULL AND bovinos != '') +
                (bufalinos IS NOT NULL AND bufalinos != '') +
                (porcinos IS NOT NULL AND porcinos != '') +
                (equinos IS NOT NULL AND equinos != '') +
                (ovinos IS NOT NULL AND ovinos != '') +
                (caprinos IS NOT NULL AND caprinos != '') = 1 THEN 'Una especie'
            WHEN 
                (bovinos IS NOT NULL AND bovinos != '') +
                (bufalinos IS NOT NULL AND bufalinos != '') +
                (porcinos IS NOT NULL AND porcinos != '') +
                (equinos IS NOT NULL AND equinos != '') +
                (ovinos IS NOT NULL AND ovinos != '') +
                (caprinos IS NOT NULL AND caprinos != '') = 2 THEN 'Dos especies'
            ELSE 'Múltiples especies'
        END AS diversidad_especies,
        COUNT(*) AS cantidad
        ")->groupBy('diversidad_especies')->get();


        $resultados = [
            'Animales en control' => DB::table('infor_epidemiologica')
                ->select('anim_enferm_control AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->whereNotNull('anim_enferm_control')
                ->where('anim_enferm_control', '!=', '')
                ->groupBy('anim_enferm_control')
                ->pluck('cantidad', 'categoria'),

            'Cantidad en control' => DB::table('infor_epidemiologica')
                ->select('anim_enferm_control_cant AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->whereNotNull('anim_enferm_control_cant')
                ->where('anim_enferm_control_cant', '!=', '')
                ->groupBy('anim_enferm_control_cant')
                ->pluck('cantidad', 'categoria'),

            'Cuadros clínicos' => DB::table('infor_epidemiologica')
                ->select('cuadr_clinc_sospec AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->whereNotNull('cuadr_clinc_sospec')
                ->where('cuadr_clinc_sospec', '!=', '')
                ->groupBy('cuadr_clinc_sospec')
                ->pluck('cantidad', 'categoria'),

            'Especies afectadas' => DB::table('infor_epidemiologica')
                ->select('especies_afectadas AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->whereNotNull('especies_afectadas')
                ->where('especies_afectadas', '!=', '')
                ->groupBy('especies_afectadas')
                ->pluck('cantidad', 'categoria'),

            'Toma de muestra' => DB::table('infor_epidemiologica')
                ->select('toma_muestra AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->whereNotNull('toma_muestra')
                ->where('toma_muestra', '!=', '')
                ->groupBy('toma_muestra')
                ->pluck('cantidad', 'categoria'),
        ];

        $resultadosRiesgo = [
            'Colindancia con riesgo' => CarazterizacionRiesgo::select('colinda_establecim_riesgo as categoria', DB::raw('COUNT(*) as cantidad'))
                ->whereNotNull('colinda_establecim_riesgo')
                ->where('colinda_establecim_riesgo', '!=', '')
                ->groupBy('colinda_establecim_riesgo')
                ->pluck('cantidad', 'categoria'),

            'Asistencia técnica' => CarazterizacionRiesgo::select('asistencia_tecnica as categoria', DB::raw('COUNT(*) as cantidad'))
                ->whereNotNull('asistencia_tecnica')
                ->where('asistencia_tecnica', '!=', '')
                ->groupBy('asistencia_tecnica')
                ->pluck('cantidad', 'categoria'),

            'Frecuencia asistencia' => CarazterizacionRiesgo::select('asistencia_tecnica_frecuen as categoria', DB::raw('COUNT(*) as cantidad'))
                ->whereNotNull('asistencia_tecnica_frecuen')
                ->where('asistencia_tecnica_frecuen', '!=', '')
                ->groupBy('asistencia_tecnica_frecuen')
                ->pluck('cantidad', 'categoria'),

            'Número de trabajadores' => CarazterizacionRiesgo::selectRaw("
                    CASE
                        WHEN num_trabajadores = 0 THEN 'Sin trabajadores'
                        WHEN num_trabajadores BETWEEN 1 AND 2 THEN '1-2 trabajadores'
                        WHEN num_trabajadores BETWEEN 3 AND 5 THEN '3-5 trabajadores'
                        WHEN num_trabajadores BETWEEN 6 AND 10 THEN '6-10 trabajadores'
                        ELSE 'Más de 10 trabajadores'
                    END AS categoria,
                    COUNT(*) AS cantidad
                ")
                ->groupBy('categoria')
                ->pluck('cantidad', 'categoria'),

            'Trabajan en otras explotaciones' => CarazterizacionRiesgo::select('trabajan_otr_explotacion as categoria', DB::raw('COUNT(*) as cantidad'))
                ->whereNotNull('trabajan_otr_explotacion')
                ->where('trabajan_otr_explotacion', '!=', '')
                ->groupBy('trabajan_otr_explotacion')
                ->pluck('cantidad', 'categoria'),
        ];

        $visitas = VisitaPrediosRiesgo::select('enferm_baj_vigil AS categoria', DB::raw('COUNT(*) AS cantidad'))
            ->whereNotNull('enferm_baj_vigil')
            ->where('enferm_baj_vigil', '!=', '')
            ->groupBy('enferm_baj_vigil')
            ->pluck('cantidad', 'categoria');

        $frecuencias = VisitaPrediosRiesgo::select('toma_muestras AS categoria', DB::raw('COUNT(*) AS cantidad'))
            ->whereNotNull('toma_muestras')
            ->where('toma_muestras', '!=', '')
            ->groupBy('toma_muestras')
            ->pluck('cantidad', 'categoria');

        $resultadosServiciosAmbientales = [
            'Conservación de Suelos' => DB::table('servicios_ambientales')
                ->select('materiales_establecidos AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->groupBy('materiales_establecidos')
                ->pluck('cantidad', 'categoria'),

            'Protección de Cuencas' => DB::table('servicios_ambientales')
                ->select('cod_tip_servicio_OF AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->groupBy('cod_tip_servicio_OF')
                ->pluck('cantidad', 'categoria'),

            'Biodiversidad' => DB::table('servicios_ambientales')
                ->select('id_tip_servicio AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->groupBy('id_tip_servicio')
                ->pluck('cantidad', 'categoria'),

            'Captura de Carbono' => DB::table('servicios_ambientales')
                ->select('hectareas AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->groupBy('hectareas')
                ->pluck('cantidad', 'categoria'),

            'Paisajismo' => DB::table('servicios_ambientales')
                ->select('sum_total AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->groupBy('sum_total')
                ->pluck('cantidad', 'categoria'),
        ];


        return view('dashboard', compact(
            'user',
            'usuariosActivos',
            'prediosCount',
            'animalesCount',
            'suscripciones',
            'totalSuscriptores',
            'nuevosSuscriptores',
            'totalRecaudado',
            'recaudadoUltimoMes',
            'suscripcionesPorMes',
            'recaudacionDetallada',
            'departamentos',
            'predios',
            'datos',
            'dataInfoTierraAgua',
            'datosAreas',
            'valoresPastos',
            'pastosCategorias',
            'valoresMA',
            'categoriasMA',
            'valoresGI',
            'valoresExplotacion',
            'diversidad',
            'resultados',
            'resultadosRiesgo',
            'visitas',
            'frecuencias',
            'resultadosServiciosAmbientales'
        ));
    }

    public function dashboardAdminDASH(Request $request)
    {
        $user = Auth::user();

        $departamentos = Predios::pluck('departamento')->unique()->values();
        $predios = Predios::select('id', 'nombre_predio')->get();

        $idPredio = $request->input('id_predio');

        $query = ManGenGanado::query();
        if ($idPredio) {
            $query->where('id_predio', $idPredio);
        }

        $datos = collect([
            'Sistema de Levante' => $query->clone()->select('sistem_levant_animal as categoria')->selectRaw('COUNT(*) as cantidad')->groupBy('sistem_levant_animal')->pluck('cantidad', 'categoria'),
            'Sistema de Alimentación' => $query->clone()->select('aliment_ternero as categoria')->selectRaw('COUNT(*) as cantidad')->groupBy('aliment_ternero')->pluck('cantidad', 'categoria'),
            'Suplementación Mineral' => $query->clone()->select('sumin_sal as categoria')->selectRaw('COUNT(*) as cantidad')->groupBy('sumin_sal')->pluck('cantidad', 'categoria'),
            'Sistema de Reproducción' => $query->clone()->select('sistem_servic_reproduct as categoria')->selectRaw('COUNT(*) as cantidad')->groupBy('sistem_servic_reproduct')->pluck('cantidad', 'categoria'),
            'Manejo Reproductivo' => $query->clone()->select('form_program_servicios as categoria')->selectRaw('COUNT(*) as cantidad')->groupBy('form_program_servicios')->pluck('cantidad', 'categoria'),
        ]);

        $filtro = json_decode($request->input('filtroPredios'), true);
        $tipo = $filtro['tipo'] ?? null;

        $query = InfoTierraAgua::query();

        if ($tipo === 'predio') {
            $query->where('id_predio', $filtro['id_predio']);
        } elseif ($tipo === 'regional') {
            $query->join('predios', 'info_tierra_agua.id_predio', '=', 'predios.id')
                ->where('predios.departamento', $filtro['departamento']);
        }

        $dataInfoTierraAgua = [
            'fuentes_agua' => (clone $query)->selectRaw("
                    CASE 
                        WHEN fuente_calidad_agua LIKE '%Rio%' THEN 'Río'
                        WHEN fuente_calidad_agua LIKE '%Pozo%' THEN 'Pozo'
                        WHEN fuente_calidad_agua LIKE '%Quebrada%' THEN 'Quebrada'
                        WHEN fuente_calidad_agua LIKE '%Jagüey%' THEN 'Jagüey'
                        WHEN fuente_calidad_agua LIKE '%Nacimiento%' THEN 'Nacimiento'
                        ELSE 'Otros'
                    END AS fuente_calidad_agua,
                    COUNT(*) AS cantidad_predios
                ")
                ->groupBy('fuente_calidad_agua')
                ->get(),

            'tipo_suelo' => (clone $query)->selectRaw("IFNULL(suelos_predominantes, 'Otro') AS tipo_suelo, COUNT(*) AS cantidad_predios")
                ->groupBy('suelos_predominantes')
                ->get(),

            'drenaje' => (clone $query)->selectRaw("IFNULL(drenaje, 'Otro') AS drenaje, COUNT(*) AS cantidad_predios")
                ->groupBy('drenaje')
                ->get(),

            'manejo_cuencas' => (clone $query)->selectRaw("IFNULL(manejo_cuencas_nac_agua, 'Otro') AS manejo_cuencas_nac_agua, COUNT(*) AS cantidad_predios")
                ->groupBy('manejo_cuencas_nac_agua')
                ->get(),
        ];

        $tipo = $request->input('tipo');
        $departamento = $request->input('departamento');
        $id_predio = $request->input('id_predio');

        $totalMedidas = Areas::sum('medidas');

        $datosAreas = Areas::selectRaw("
        tipo_areas.nombre_area AS tipo_area,
        COUNT(areas.id) AS cantidad_predios,
        SUM(areas.medidas) AS area_total,
        ROUND((SUM(areas.medidas) * 100.0 / ?), 2) AS porcentaje
        ", [$totalMedidas])
            ->join('tipo_areas', 'areas.id_tipo_area', '=', 'tipo_areas.id')
            ->groupBy('tipo_areas.nombre_area')
            ->orderByDesc('area_total')
            ->get();


        $pastosCategorias = [
            'Renovación de Pastos' => 'div_potreros_como',
            'División de Potreros' => 'div_potreros',
            'Tipo de Cerca' => 'cercas',
            'Rotación de Potreros' => 'tipo_pastoreo',
        ];

        $valoresPastos = collect();

        $valoresPastos = [];

        foreach ($pastosCategorias as $nombreCategoria => $campo) {
            $conteo = ManPastPotrCer::select($campo, DB::raw('COUNT(*) as cantidad_predios'))
                ->where($campo, '!=', '')
                ->groupBy($campo)
                ->orderByDesc('cantidad_predios')
                ->get();

            $valoresPastos[$nombreCategoria] = $conteo->pluck('cantidad_predios', $campo)->toArray();
        }


        $valoresMA = collect();

        $categoriasMA = [
            'Manejo de Basuras' => 'manejo_basuras',
            'Disposición de Aguas Servidas' => 'dispos_aguas_servid',
            'Disposición de Excremento de Bovinos' => 'dispos_excrement_bovinos',
            'Manejo de Empaques de Productos Químicos' => 'manejo_empaq_produc_quimic',
        ];

        foreach ($categoriasMA as $nombreCategoria => $campo) {
            $resultados = DB::table('inform_aspect_med_ambient')
                ->select($campo, DB::raw('COUNT(*) as cantidad_predios'))
                ->groupBy($campo)
                ->orderByDesc('cantidad_predios')
                ->get();

            $valoresMA[$nombreCategoria] = $resultados->pluck('cantidad_predios', $campo)->toArray();
        }


        $valoresGI = collect();

        // LLEVA REGISTROS
        $llevaRegistros = DB::table('gestion_informacion')
            ->select('donde_regis_info_finca as lleva_registros', DB::raw('COUNT(*) as cantidad_predios'))
            ->groupBy('donde_regis_info_finca')
            ->get();

        $valoresGI['Lleva Registros'] = $llevaRegistros->map(function ($item) {
            return [
                'categoria' => $item->lleva_registros,
                'cantidad' => $item->cantidad_predios
            ];
        });

        // TIPO DE REGISTROS
        $tipoRegistros = DB::table('gestion_informacion')
            ->select('los_registros_son as tipo_registros', DB::raw('COUNT(*) as cantidad_predios'))
            ->whereNotNull('los_registros_son')
            ->groupBy('los_registros_son')
            ->get();

        $valoresGI['Tipo de Registros'] = $tipoRegistros->map(function ($item) {
            return [
                'categoria' => $item->tipo_registros,
                'cantidad' => $item->cantidad_predios
            ];
        });

        // FRECUENCIA DE REGISTROS
        $frecuenciaRegistros = DB::table('gestion_informacion')
            ->select('calcula_indicadores_de as frecuencia_registros', DB::raw('COUNT(*) as cantidad_predios'))
            ->whereNotNull('calcula_indicadores_de')
            ->groupBy('calcula_indicadores_de')
            ->get();

        $valoresGI['Frecuencia de Registros'] = $frecuenciaRegistros->map(function ($item) {
            return [
                'categoria' => $item->frecuencia_registros,
                'cantidad' => $item->cantidad_predios
            ];
        });

        // HERRAMIENTAS INFORMÁTICAS
        $herramientasInformaticas = DB::table('gestion_informacion')
            ->select('utiliza_software_monitore as herramientas_informaticas', DB::raw('COUNT(*) as cantidad_predios'))
            ->groupBy('utiliza_software_monitore')
            ->get();

        $valoresGI['Herramientas Informáticas'] = $herramientasInformaticas->map(function ($item) {
            return [
                'categoria' => $item->herramientas_informaticas,
                'cantidad' => $item->cantidad_predios
            ];
        });

        $especies = [
            'Bovinos'   => 'bovinos',
            'Bufalinos' => 'bufalinos',
            'Porcinos'  => 'porcinos',
            'Equinos'   => 'equinos',
            'Ovinos'    => 'ovinos',
            'Caprinos'  => 'caprinos',
        ];

        $valoresExplotacion = collect();

        foreach ($especies as $nombre => $campo) {
            $cantidad = DB::table('tp_explotacion')
                ->whereNotNull($campo)
                ->where($campo, '!=', '')
                ->count();

            $valoresExplotacion[$nombre] = $cantidad;
        }

        $diversidad = DB::table('tp_explotacion')->selectRaw("
        CASE
            WHEN 
                (bovinos IS NOT NULL AND bovinos != '') +
                (bufalinos IS NOT NULL AND bufalinos != '') +
                (porcinos IS NOT NULL AND porcinos != '') +
                (equinos IS NOT NULL AND equinos != '') +
                (ovinos IS NOT NULL AND ovinos != '') +
                (caprinos IS NOT NULL AND caprinos != '') = 1 THEN 'Una especie'
            WHEN 
                (bovinos IS NOT NULL AND bovinos != '') +
                (bufalinos IS NOT NULL AND bufalinos != '') +
                (porcinos IS NOT NULL AND porcinos != '') +
                (equinos IS NOT NULL AND equinos != '') +
                (ovinos IS NOT NULL AND ovinos != '') +
                (caprinos IS NOT NULL AND caprinos != '') = 2 THEN 'Dos especies'
            ELSE 'Múltiples especies'
        END AS diversidad_especies,
        COUNT(*) AS cantidad
        ")->groupBy('diversidad_especies')->get();


        $resultados = [
            'Animales en control' => DB::table('infor_epidemiologica')
                ->select('anim_enferm_control AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->whereNotNull('anim_enferm_control')
                ->where('anim_enferm_control', '!=', '')
                ->groupBy('anim_enferm_control')
                ->pluck('cantidad', 'categoria'),

            'Cantidad en control' => DB::table('infor_epidemiologica')
                ->select('anim_enferm_control_cant AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->whereNotNull('anim_enferm_control_cant')
                ->where('anim_enferm_control_cant', '!=', '')
                ->groupBy('anim_enferm_control_cant')
                ->pluck('cantidad', 'categoria'),

            'Cuadros clínicos' => DB::table('infor_epidemiologica')
                ->select('cuadr_clinc_sospec AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->whereNotNull('cuadr_clinc_sospec')
                ->where('cuadr_clinc_sospec', '!=', '')
                ->groupBy('cuadr_clinc_sospec')
                ->pluck('cantidad', 'categoria'),

            'Especies afectadas' => DB::table('infor_epidemiologica')
                ->select('especies_afectadas AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->whereNotNull('especies_afectadas')
                ->where('especies_afectadas', '!=', '')
                ->groupBy('especies_afectadas')
                ->pluck('cantidad', 'categoria'),

            'Toma de muestra' => DB::table('infor_epidemiologica')
                ->select('toma_muestra AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->whereNotNull('toma_muestra')
                ->where('toma_muestra', '!=', '')
                ->groupBy('toma_muestra')
                ->pluck('cantidad', 'categoria'),
        ];

        $resultadosRiesgo = [
            'Colindancia con riesgo' => CarazterizacionRiesgo::select('colinda_establecim_riesgo as categoria', DB::raw('COUNT(*) as cantidad'))
                ->whereNotNull('colinda_establecim_riesgo')
                ->where('colinda_establecim_riesgo', '!=', '')
                ->groupBy('colinda_establecim_riesgo')
                ->pluck('cantidad', 'categoria'),

            'Asistencia técnica' => CarazterizacionRiesgo::select('asistencia_tecnica as categoria', DB::raw('COUNT(*) as cantidad'))
                ->whereNotNull('asistencia_tecnica')
                ->where('asistencia_tecnica', '!=', '')
                ->groupBy('asistencia_tecnica')
                ->pluck('cantidad', 'categoria'),

            'Frecuencia asistencia' => CarazterizacionRiesgo::select('asistencia_tecnica_frecuen as categoria', DB::raw('COUNT(*) as cantidad'))
                ->whereNotNull('asistencia_tecnica_frecuen')
                ->where('asistencia_tecnica_frecuen', '!=', '')
                ->groupBy('asistencia_tecnica_frecuen')
                ->pluck('cantidad', 'categoria'),

            'Número de trabajadores' => CarazterizacionRiesgo::selectRaw("
                    CASE
                        WHEN num_trabajadores = 0 THEN 'Sin trabajadores'
                        WHEN num_trabajadores BETWEEN 1 AND 2 THEN '1-2 trabajadores'
                        WHEN num_trabajadores BETWEEN 3 AND 5 THEN '3-5 trabajadores'
                        WHEN num_trabajadores BETWEEN 6 AND 10 THEN '6-10 trabajadores'
                        ELSE 'Más de 10 trabajadores'
                    END AS categoria,
                    COUNT(*) AS cantidad
                ")
                ->groupBy('categoria')
                ->pluck('cantidad', 'categoria'),

            'Trabajan en otras explotaciones' => CarazterizacionRiesgo::select('trabajan_otr_explotacion as categoria', DB::raw('COUNT(*) as cantidad'))
                ->whereNotNull('trabajan_otr_explotacion')
                ->where('trabajan_otr_explotacion', '!=', '')
                ->groupBy('trabajan_otr_explotacion')
                ->pluck('cantidad', 'categoria'),
        ];

        $visitas = VisitaPrediosRiesgo::select('enferm_baj_vigil AS categoria', DB::raw('COUNT(*) AS cantidad'))
            ->whereNotNull('enferm_baj_vigil')
            ->where('enferm_baj_vigil', '!=', '')
            ->groupBy('enferm_baj_vigil')
            ->pluck('cantidad', 'categoria');

        $frecuencias = VisitaPrediosRiesgo::select('toma_muestras AS categoria', DB::raw('COUNT(*) AS cantidad'))
            ->whereNotNull('toma_muestras')
            ->where('toma_muestras', '!=', '')
            ->groupBy('toma_muestras')
            ->pluck('cantidad', 'categoria');

        $resultadosServiciosAmbientales = [
            'Conservación de Suelos' => DB::table('servicios_ambientales')
                ->select('materiales_establecidos AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->groupBy('materiales_establecidos')
                ->pluck('cantidad', 'categoria'),

            'Protección de Cuencas' => DB::table('servicios_ambientales')
                ->select('cod_tip_servicio_OF AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->groupBy('cod_tip_servicio_OF')
                ->pluck('cantidad', 'categoria'),

            'Biodiversidad' => DB::table('servicios_ambientales')
                ->select('id_tip_servicio AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->groupBy('id_tip_servicio')
                ->pluck('cantidad', 'categoria'),

            'Captura de Carbono' => DB::table('servicios_ambientales')
                ->select('hectareas AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->groupBy('hectareas')
                ->pluck('cantidad', 'categoria'),

            'Paisajismo' => DB::table('servicios_ambientales')
                ->select('sum_total AS categoria', DB::raw('COUNT(*) AS cantidad'))
                ->groupBy('sum_total')
                ->pluck('cantidad', 'categoria'),
        ];


        return [
            'datos' => $datos,
            'dataInfoTierraAgua' => $dataInfoTierraAgua,
            'datosAreas' => $datosAreas,
            'valoresPastos' => $valoresPastos,
            'pastosCategorias' => $pastosCategorias,
            'valoresMA' => $valoresMA,
            'categoriasMA' => $categoriasMA,
            'valoresGI' => $valoresGI,
            'valoresExplotacion' => $valoresExplotacion,
            'diversidad' => $diversidad,
            'resultados' => $resultados,
            'resultadosRiesgo' => $resultadosRiesgo,
            'visitas' => $visitas,
            'frecuencias' => $frecuencias,
            'resultadosServiciosAmbientales' => $resultadosServiciosAmbientales
        ];
    }

    public function exportarDashboardExcel(Request $request)
    {
        // Obtener todos los datos del dashboard
        $datos = $this->dashboardAdminDASH($request);

        return Excel::download(new DashboardExports($datos), 'dashboard_completo.xlsx');
    }
}
