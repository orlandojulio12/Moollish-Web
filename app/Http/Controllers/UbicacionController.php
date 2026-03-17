<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Ubicacion;
use App\Models\Potrero;
use App\Models\Lote;
use App\Models\Predios;
use App\Models\Animal;
use App\Models\Movimiento;
use App\Models\Movimientos;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class UbicacionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Consulta base para obtener los animales vivos
        $animalesQuery = Animal::with(['predio', 'lote', 'potrero'])
            ->where('estado_vida', Animal::ESTADO_VIVO);

        // Si el usuario no es administrador, filtrar por sus predios
        if ($user->role->name !== 'admin') {
            $predios = $user->predios()->pluck('predios.id')->toArray();
            $animalesQuery->whereIn('id_predio', $predios);
        }

        // Obtener los animales con el filtro aplicado
        $animales = $animalesQuery->get();

        // Obtener información adicional (predios, lotes, potreros)
        $predios = $user->role->name === 'admin'
            ? Predios::withCount(['animales' => function ($query) {
                $query->where('estado_vida', Animal::ESTADO_VIVO);
            }])->get()
            : $user->predios()->withCount(['animales' => function ($query) {
                $query->where('estado_vida', Animal::ESTADO_VIVO);
            }])->get();

        $lotes = Lote::whereIn('predio_id', $predios->pluck('id'))
            ->withCount(['animales' => function ($query) {
                $query->where('estado_vida', Animal::ESTADO_VIVO);
            }])->get();

        $potreros = Potrero::whereIn('predio_id', $predios->pluck('id'))
            ->withCount(['animales' => function ($query) {
                $query->where('estado_vida', Animal::ESTADO_VIVO);
            }])->get();

        $movimientos = Movimiento::whereIn('predio_id', $predios->pluck('id'))
            ->with(['animal', 'predio', 'lote', 'potrero'])
            ->get();

        // Retornar la vista con los datos
        return view('inventario_animales.Ubicacion', compact(
            'predios',
            'lotes',
            'potreros',
            'movimientos',
            'animales'
        ));
    }


    public function getAnimalesByPotrero($potreroId)
    {
        $user =Auth::user();


        // Filtrar animales vivos y, si no es admin, solo los predios del usuario
        $animales = Animal::where('potrero_id', $potreroId)
            ->where('estado_vida', Animal::ESTADO_VIVO);

        if ($user->role->name !== 'admin') {
            $prediosUsuario = $user->predios->pluck('id')->toArray();
            $animales->whereIn('id_predio', $prediosUsuario);
        }

        return response()->json([
            'success' => true,
            'animales' => $animales->get(),
        ]);
    }

    public function getAnimalesByPredio($predioId)
    {
        $user =Auth::user();


        // Filtrar animales vivos y, si no es admin, validar el acceso al predio
        $animales = Animal::where('id_predio', $predioId)
            ->where('estado_vida', Animal::ESTADO_VIVO);

        if ($user->role->name !== 'admin') {
            $prediosUsuario = $user->predios->pluck('id')->toArray();
            if (!in_array($predioId, $prediosUsuario)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para acceder a este predio.',
                ], 403);
            }
        }

        return response()->json([
            'success' => true,
            'animales' => $animales->get(),
        ]);
    }

    public function getAnimalesByLote($loteId)
    {
        $user = Auth::user();

        $animales = Animal::where('lote_id', $loteId)
            ->where('estado_vida', Animal::ESTADO_VIVO);

        if ($user->role->name !== 'admin') {
            $prediosUsuario = $user->predios->pluck('id')->toArray();
            $animales->whereIn('id_predio', $prediosUsuario);
        }

        return response()->json([
            'success' => true,
            'animales' => $animales->get(),
        ]);
    }



    public function store(Request $request)
    {
        try {
            // Validar la entrada
            $request->validate([
                'id_animal' => 'nullable|array', // id_animal es ahora nullable
                'id_animal.*' => 'exists:animales,id_animal', // Validar que el animal existe si se proporciona
                'lote' => 'nullable|string|exists:lotes,id', // Validar que el lote existe si se proporciona
                'potrero' => 'nullable|string|exists:potreros,id', // Validar que el potrero existe si se proporciona
                'fecha_movimiento' => 'required|date', // Validar que la fecha sea válida
                'motivo' => 'nullable|string', // Motivo opcional
            ]);

            // Obtener el lote y el potrero basado en el ID si se proporciona
            $lote = $request->lote ? Lote::where('id', $request->lote)->first() : null;
            $potrero = $request->potrero ? Potrero::where('id', $request->potrero)->first() : null;

            // Obtener el predio asociado al primer animal seleccionado si hay un animal seleccionado
            $predio = null;

            // Lógica para procesar los movimientos
            $movimientosRealizados = [];

            // Procesamiento de los animales seleccionados...
            // [Aquí va el resto del código para procesar la ubicación]

            return response()->json([
                'success' => true,
                'message' => 'Ubicación registrada correctamente',
                'movimientos' => $movimientosRealizados
            ]);
        } catch (\Exception $e) {
            Log::error('Error en store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Hubo un error al registrar la ubicación: ' . $e->getMessage()
            ], 500);
        }
    }
    public function storePotrero(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validated = $request->validate([
                'predio_id' => 'required|exists:predios,id', // Asegura que el predio_id exista en la tabla de predios
                'nombre' => 'required|string|max:255', // Nombre es obligatorio y debe ser una cadena de texto
                'area' => 'nullable|integer|min:1', // Capacidad es obligatoria y debe ser un número entero positivo
            ]);

            // Crear el nuevo potrero
            Potrero::create($validated);

            // Redirigir con un mensaje de éxito
            return redirect()->back()->with('success', 'Potrero creado exitosamente');
        } catch (Exception $e) {
            // En caso de error, redirigir con un mensaje de error
            return redirect()->back()->with('error', 'Hubo un problema al crear el potrero: ' . $e->getMessage());
        }
    }
    public function storeLote(Request $request)
{
    try {
        $validated = $request->validate([
            'predio_id' => 'required|exists:predios,id', // Validación para asegurar que el predio existe
            'nombre' => 'required|string|max:255',

        ]);

        Lote::create($validated);

        return redirect()->back()->with('success', 'Lote creado exitosamente');
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Hubo un problema al crear el lote: ' . $e->getMessage());
    }
}


/**
 * Listado de traslados / movimientos.
 * GET /reportes/traslados
 */
// public function reporteTrasladosListado(Request $request)
// {
//     $user    = Auth::user();
//     $esAdmin = $user->role->name === 'admin';

//     $predios = $esAdmin
//         ? Predios::orderBy('nombre_predio')->get()
//         : $user->predios()->orderBy('nombre_predio')->get();

//     $predioIds = $predios->pluck('id');

//     // ── QUERY PRINCIPAL ───────────────────────────────────────────────
//     $query = \DB::table('movimientos_animales as ma')
//         ->join('animales as a',          'a.id_animal',  '=', 'ma.animal_id')
//         // Destino
//         ->leftJoin('predios as pd',      'pd.id',        '=', 'ma.predio_id')
//         ->leftJoin('lotes as ld',        'ld.id',        '=', 'ma.lote_id')
//         ->leftJoin('potreros as pod',    'pod.id',       '=', 'ma.potrero_id')
//         // Origen (campos nuevos agregados por migración)
//         ->leftJoin('predios as po',      'po.id',        '=', 'ma.predio_origen_id')
//         ->leftJoin('lotes as lo',        'lo.id',        '=', 'ma.lote_origen_id')
//         ->leftJoin('potreros as poo',    'poo.id',       '=', 'ma.potrero_origen_id')
//         // Lote auto-creado (escenario 3)
//         ->leftJoin('lotes as ln',        'ln.id',        '=', 'ma.lote_nuevo_id')

//         ->select([
//             'ma.id',
//             'ma.animal_id',
//             'ma.fecha_movimiento',
//             'ma.motivo',
//             'ma.tipo_traslado',
//             'ma.created_at',          // usado para agrupar evento masivo

//             // Animal
//             'a.codigo     as animal_codigo',
//             'a.nombre     as animal_nombre',
//             'a.sexo       as animal_sexo',

//             // Destino
//             'ma.predio_id',
//             'ma.lote_id',
//             'ma.potrero_id',
//             'pd.nombre_predio as destino_predio',
//             'ld.nombre        as destino_lote',
//             'pod.nombre       as destino_potrero',

//             // Origen
//             'ma.predio_origen_id',
//             'ma.lote_origen_id',
//             'ma.potrero_origen_id',
//             'po.nombre_predio as origen_predio',
//             'lo.nombre        as origen_lote',
//             'poo.nombre       as origen_potrero',

//             // Lote nuevo
//             'ma.lote_nuevo_id',
//             'ln.nombre        as lote_nuevo_nombre',
//         ])

//         ->whereIn('ma.predio_id', $predioIds)
//         ->orderByDesc('ma.fecha_movimiento')
//         ->orderByDesc('ma.id');

//     // ── FILTROS ───────────────────────────────────────────────────────
//     if ($request->filled('fecha_desde')) {
//         $query->where('ma.fecha_movimiento', '>=', $request->fecha_desde);
//     }
//     if ($request->filled('fecha_hasta')) {
//         $query->where('ma.fecha_movimiento', '<=', $request->fecha_hasta);
//     }
//     if ($request->filled('predio_id')) {
//         $query->where('ma.predio_id', $request->predio_id);
//     }
//     if ($request->filled('lote_id')) {
//         $query->where(function ($q) use ($request) {
//             $q->where('ma.lote_id', $request->lote_id)
//               ->orWhere('ma.lote_origen_id', $request->lote_id);
//         });
//     }
//     if ($request->filled('potrero_id')) {
//         $query->where(function ($q) use ($request) {
//             $q->where('ma.potrero_id', $request->potrero_id)
//               ->orWhere('ma.potrero_origen_id', $request->potrero_id);
//         });
//     }
//     if ($request->filled('tipo_traslado')) {
//         $query->where('ma.tipo_traslado', $request->tipo_traslado);
//     }
//     if ($request->filled('animal_busq')) {
//         $t = '%' . $request->animal_busq . '%';
//         $query->where(function ($q) use ($t) {
//             $q->where('a.codigo', 'like', $t)
//               ->orWhere('a.nombre', 'like', $t);
//         });
//     }

//     // ── PAGINACIÓN ────────────────────────────────────────────────────
//     $perPage     = (int) ($request->per_page ?? 20);
//     $movimientos = $query->paginate($perPage)->withQueryString();

//     // ── ESTADÍSTICAS ─────────────────────────────────────────────────
//     $stats = \DB::table('movimientos_animales as ma')
//         ->join('animales as a', 'a.id_animal', '=', 'ma.animal_id')
//         ->whereIn('ma.predio_id', $predioIds)
//         ->when($request->filled('fecha_desde'),    fn($q) => $q->where('ma.fecha_movimiento', '>=', $request->fecha_desde))
//         ->when($request->filled('fecha_hasta'),    fn($q) => $q->where('ma.fecha_movimiento', '<=', $request->fecha_hasta))
//         ->when($request->filled('predio_id'),      fn($q) => $q->where('ma.predio_id', $request->predio_id))
//         ->when($request->filled('tipo_traslado'),  fn($q) => $q->where('ma.tipo_traslado', $request->tipo_traslado))
//         ->selectRaw("
//             COUNT(*)                                                                      as total,
//             SUM(ma.tipo_traslado = 'masivo')                                              as total_masivos,
//             SUM(ma.tipo_traslado = 'individual' OR ma.tipo_traslado IS NULL)              as total_individuales,
//             COUNT(DISTINCT ma.animal_id)                                                  as total_animales_movidos,
//             SUM(ma.lote_nuevo_id IS NOT NULL)                                             as lotes_creados
//         ")
//         ->first();

//     // Total exclusiones del período filtrado
//     $totalExcluidos = \DB::table('traslado_exclusiones')
//         ->whereIn('predio_id', $predioIds)
//         ->when($request->filled('fecha_desde'), fn($q) => $q->where('fecha', '>=', $request->fecha_desde))
//         ->when($request->filled('fecha_hasta'), fn($q) => $q->where('fecha', '<=', $request->fecha_hasta))
//         ->count();

//     // ── DATOS PARA FILTROS ────────────────────────────────────────────
//     $lotes = Lote::whereIn('predio_id', $predioIds)->orderBy('nombre')->get(['id', 'nombre', 'predio_id']);
//     $potreros = Potrero::whereIn('predio_id', $predioIds)->orderBy('nombre')->get(['id', 'nombre', 'predio_id']);

//     return view('reportes.traslados', compact(
//         'predios', 'lotes', 'potreros',
//         'movimientos', 'stats', 'totalExcluidos', 'perPage'
//     ));
// }

// ─────────────────────────────────────────────────────────────────────────────

/**
 * AJAX: detalle completo de un movimiento.
 * GET /reportes/traslados/detalle/{id}
 */
public function reporteTrasladoDetalle($id)
{
    $user    = Auth::user();
    $esAdmin = $user->role->name === 'admin';

    $mov = \DB::table('movimientos_animales as ma')
        ->join('animales as a',       'a.id_animal', '=', 'ma.animal_id')
        ->leftJoin('predios as pd',   'pd.id',       '=', 'ma.predio_id')
        ->leftJoin('lotes as ld',     'ld.id',       '=', 'ma.lote_id')
        ->leftJoin('potreros as pod', 'pod.id',      '=', 'ma.potrero_id')
        ->leftJoin('predios as po',   'po.id',       '=', 'ma.predio_origen_id')
        ->leftJoin('lotes as lo',     'lo.id',       '=', 'ma.lote_origen_id')
        ->leftJoin('potreros as poo', 'poo.id',      '=', 'ma.potrero_origen_id')
        ->leftJoin('lotes as ln',     'ln.id',       '=', 'ma.lote_nuevo_id')
        ->select([
            'ma.id',
            'ma.animal_id',
            'ma.fecha_movimiento',
            'ma.motivo',
            'ma.tipo_traslado',
            'ma.created_at',
            'ma.predio_id',
            'ma.lote_id',
            'ma.potrero_id',
            'ma.predio_origen_id',
            'ma.lote_origen_id',
            'ma.potrero_origen_id',
            'ma.lote_nuevo_id',
            'a.codigo   as animal_codigo',
            'a.nombre   as animal_nombre',
            'a.sexo     as animal_sexo',
            'pd.nombre_predio as destino_predio',
            'ld.nombre        as destino_lote',
            'pod.nombre       as destino_potrero',
            'po.nombre_predio as origen_predio',
            'lo.nombre        as origen_lote',
            'poo.nombre       as origen_potrero',
            'ln.nombre        as lote_nuevo_nombre',
        ])
        ->where('ma.id', $id)
        ->first();

    if (!$mov) {
        return response()->json(['success' => false, 'message' => 'Movimiento no encontrado.'], 404);
    }

    // Seguridad
    if (!$esAdmin) {
        $prediosUsuario = $user->predios()->pluck('predios.id')->toArray();
        if (!in_array($mov->predio_id, $prediosUsuario)) {
            return response()->json(['success' => false, 'message' => 'Sin permiso.'], 403);
        }
    }

    $grupo     = null;
    $excluidos = null;

    if ($mov->tipo_traslado === 'masivo') {
        // ── Agrupar animales del mismo evento masivo ──────────────────
        // Como traslado_ref NO existe en movimientos_animales, agrupamos
        // por: mismo predio destino + mismo lote destino + misma fecha
        // + tipo masivo + created_at dentro del mismo segundo (misma tx)
        $grupo = \DB::table('movimientos_animales as ma')
            ->join('animales as a', 'a.id_animal', '=', 'ma.animal_id')
            ->where('ma.tipo_traslado', 'masivo')
            ->where('ma.predio_id',     $mov->predio_id)
            ->where('ma.fecha_movimiento', $mov->fecha_movimiento)
            ->where('ma.lote_id',  $mov->lote_id)           // null == null funciona en PHP pero no en SQL
            ->when(
                is_null($mov->lote_id),
                fn($q) => $q->whereNull('ma.lote_id'),
                fn($q) => $q->where('ma.lote_id', $mov->lote_id)
            )
            ->when(
                is_null($mov->potrero_id),
                fn($q) => $q->whereNull('ma.potrero_id'),
                fn($q) => $q->where('ma.potrero_id', $mov->potrero_id)
            )
            // Mismo segundo de inserción = misma transacción
            ->whereRaw("DATE_FORMAT(ma.created_at, '%Y-%m-%d %H:%i:%s') = DATE_FORMAT(?, '%Y-%m-%d %H:%i:%s')", [$mov->created_at])
            ->select('ma.id', 'a.codigo', 'a.nombre', 'a.sexo')
            ->orderBy('a.codigo')
            ->get();

        // ── Excluidos del mismo evento ────────────────────────────────
        // traslado_exclusiones SÍ tiene traslado_ref.
        // Buscamos exclusiones que coincidan con la misma fecha y predio
        // (sin traslado_ref en movimientos no podemos hacer match exacto,
        //  pero fecha + predio es suficientemente preciso para el reporte).
        $excluidos = \DB::table('traslado_exclusiones as te')
            ->join('animales as a', 'a.id_animal', '=', 'te.animal_id')
            ->where('te.predio_id', $mov->predio_id)        // predio donde quedaron
            ->where('te.fecha',     $mov->fecha_movimiento)
            ->select('te.id', 'a.codigo', 'a.nombre', 'a.sexo', 'te.motivo', 'te.traslado_ref')
            ->orderBy('a.codigo')
            ->get();
    }

    return response()->json([
        'success'    => true,
        'movimiento' => $mov,
        'grupo'      => $grupo,
        'excluidos'  => $excluidos,
    ]);
}

// ─────────────────────────────────────────────────────────────────────────────

/**
 * AJAX: historial de traslados de un animal.
 * GET /reportes/traslados/animal?animal_id=X
 */
public function reporteMovimientosPorAnimal(Request $request)
{
    $animalId = $request->animal_id;
    $user     = Auth::user();

    if ($user->role->name !== 'admin') {
        $prediosUsuario = $user->predios()->pluck('predios.id')->toArray();
        $existe = Animal::where('id_animal', $animalId)
            ->whereIn('id_predio', $prediosUsuario)
            ->exists();
        if (!$existe) {
            return response()->json(['success' => false, 'message' => 'Sin permiso.'], 403);
        }
    }

    $movimientos = \DB::table('movimientos_animales as ma')
        ->leftJoin('predios as pd',  'pd.id',  '=', 'ma.predio_id')
        ->leftJoin('lotes as ld',    'ld.id',  '=', 'ma.lote_id')
        ->leftJoin('potreros as pod','pod.id', '=', 'ma.potrero_id')
        ->leftJoin('predios as po',  'po.id',  '=', 'ma.predio_origen_id')
        ->leftJoin('lotes as lo',    'lo.id',  '=', 'ma.lote_origen_id')
        ->leftJoin('potreros as poo','poo.id', '=', 'ma.potrero_origen_id')
        ->leftJoin('lotes as ln',    'ln.id',  '=', 'ma.lote_nuevo_id')
        ->where('ma.animal_id', $animalId)
        ->select([
            'ma.id',
            'ma.fecha_movimiento',
            'ma.motivo',
            'ma.tipo_traslado',
            'pd.nombre_predio as destino_predio',
            'ld.nombre        as destino_lote',
            'pod.nombre       as destino_potrero',
            'po.nombre_predio as origen_predio',
            'lo.nombre        as origen_lote',
            'poo.nombre       as origen_potrero',
            'ln.nombre        as lote_nuevo_nombre',
        ])
        ->orderByDesc('ma.fecha_movimiento')
        ->orderByDesc('ma.id')
        ->get();

    $agrupados = [
        'masivo'     => $movimientos->where('tipo_traslado', 'masivo')->values(),
        'individual' => $movimientos->whereIn('tipo_traslado', ['individual', null])->values(),
    ];

    return response()->json([
        'success'     => true,
        'movimientos' => $agrupados,
        'total'       => $movimientos->count(),
    ]);
}

/**
 * Obtiene los movimientos de un animal específico vía AJAX.
 */
public function movimientosPorAnimal(Request $request)
{
    $animalId = $request->animal_id;
    $user = Auth::user();

    // 1. Validar la solicitud
    if (!$animalId) {
        return response()->json([
            'success' => false,
            'message' => 'Animal no especificado.'
        ]);
    }

    // 2. Obtener el animal y verificar permisos
    $animal = Animal::where('id_animal', $animalId)
        ->with('predio')
        ->first();

    if (!$animal) {
        return response()->json([
            'success' => false,
            'message' => 'Animal no encontrado.'
        ]);
    }

    if ($user->role->name !== 'admin') {
        $prediosUsuario = $user->predios->pluck('id')->toArray();
        if (!in_array($animal->id_predio, $prediosUsuario)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver este animal.'
            ], 403);
        }
    }

    // 3. Obtener todos los movimientos del animal ordenados ascendentemente
    $movimientos = Movimiento::where('animal_id', $animalId)
        ->orderBy('fecha_movimiento', 'asc')
        ->get();

    // Inicializar arrays para cada tipo de ubicación
    $movimientosPorTipo = [
        'predio' => [],
        'lote' => [],
        'potrero' => []
    ];

    // Mantener el seguimiento de la última ubicación por tipo
    $ultimaUbicacion = [
        'predio' => null,
        'lote' => null,
        'potrero' => null
    ];

    foreach ($movimientos as $mov) {
        // Procesar Predio
        if ($mov->predio_id) {
            $tipo = 'predio';
            $origen = $ultimaUbicacion[$tipo] ?? [
                'tipo' => 'no_definida',
                'nombre' => 'No definida'
            ];
            $destino = $this->resolverUbicacion($mov->predio_id, null, null, $tipo);

            $movimientosPorTipo[$tipo][] = [
                'fecha_movimiento' => $mov->fecha_movimiento,
                'origen' => $origen,
                'destino' => $destino,
                'motivo' => $mov->motivo
            ];

            // Actualizar última ubicación
            $ultimaUbicacion[$tipo] = $destino;
        }

        // Procesar Lote
        if ($mov->lote_id) {
            $tipo = 'lote';
            $origen = $ultimaUbicacion[$tipo] ?? [
                'tipo' => 'no_definida',
                'nombre' => 'No definida'
            ];
            $destino = $this->resolverUbicacion(null, $mov->lote_id, null, $tipo);

            $movimientosPorTipo[$tipo][] = [
                'fecha_movimiento' => $mov->fecha_movimiento,
                'origen' => $origen,
                'destino' => $destino,
                'motivo' => $mov->motivo
            ];

            // Actualizar última ubicación
            $ultimaUbicacion[$tipo] = $destino;
        }

        // Procesar Potrero
        if ($mov->potrero_id) {
            $tipo = 'potrero';
            $origen = $ultimaUbicacion[$tipo] ?? [
                'tipo' => 'no_definida',
                'nombre' => 'No definida'
            ];
            $destino = $this->resolverUbicacion(null, null, $mov->potrero_id, $tipo);

            $movimientosPorTipo[$tipo][] = [
                'fecha_movimiento' => $mov->fecha_movimiento,
                'origen' => $origen,
                'destino' => $destino,
                'motivo' => $mov->motivo
            ];

            // Actualizar última ubicación
            $ultimaUbicacion[$tipo] = $destino;
        }
    }

    // Ordenar cada tipo de movimientos descendentemente por fecha
    foreach ($movimientosPorTipo as $tipo => $movs) {
        $movimientosPorTipo[$tipo] = collect($movs)
            ->sortByDesc('fecha_movimiento')
            ->values()
            ->toArray();
    }

    return response()->json([
        'success' => true,
        'movimientos' => $movimientosPorTipo
    ]);
}

/**
 * Resuelve la ubicación de un movimiento según el tipo.
 * Devuelve un array con 'tipo' y 'nombre'.
 *
 * @param int|null $predioId
 * @param int|null $loteId
 * @param int|null $potreroId
 * @param string $tipo
 * @return array
 */
private function resolverUbicacion($predioId = null, $loteId = null, $potreroId = null, $tipo = null)
{
    switch ($tipo) {
        case 'predio':
            if ($predioId) {
                $predio = Predios::find($predioId);
                if ($predio) {
                    return [
                        'tipo' => 'predio',
                        'nombre' => $predio->nombre_predio
                    ];
                }
            }
            break;

        case 'lote':
            if ($loteId) {
                $lote = Lote::find($loteId);
                if ($lote) {
                    return [
                        'tipo' => 'lote',
                        'nombre' => $lote->nombre
                    ];
                }
            }
            break;

        case 'potrero':
            if ($potreroId) {
                $potrero = Potrero::find($potreroId);
                if ($potrero) {
                    return [
                        'tipo' => 'potrero',
                        'nombre' => $potrero->nombre
                    ];
                }
            }
            break;
    }

    return [
        'tipo' => 'no_definida',
        'nombre' => 'No definida'
    ];
}
/**
 * Muestra el formulario de traslado masivo.
 * GET /traslado/masivo
 */
public function trasladoMasivoIndex()
{
    $user    = Auth::user();
    $predios = $user->role->name === 'admin'
        ? Predios::all()
        : $user->predios()->get();

    $predioIds = $predios->pluck('id');

    $lotes = Lote::whereIn('predio_id', $predioIds)
        ->withCount(['animales' => fn($q) => $q->where('estado_vida', Animal::ESTADO_VIVO)])
        ->with('predio')
        ->get();

    $potreros = Potrero::whereIn('predio_id', $predioIds)
        ->withCount(['animales' => fn($q) => $q->where('estado_vida', Animal::ESTADO_VIVO)])
        ->with('predio')
        ->get();

    return view('inventario_animales.traslado_masivo', compact('predios', 'lotes', 'potreros'));
}

// ─────────────────────────────────────────────────────────────────────────────

/**
 * Preview AJAX: retorna los animales vivos del origen seleccionado.
 * GET /traslado/masivo/preview?tipo=lote&id=5
 *
 * [API MÓVIL] Este mismo endpoint puede usarse desde la app móvil.
 * Retorna JSON puro, no requiere cambios para la API.
 */
public function trasladoMasivoPreview(Request $request)
{
    $user = Auth::user();
    $tipo = $request->tipo; // 'lote' o 'potrero'
    $id   = $request->id;

    if (!$tipo || !$id) {
        return response()->json(['success' => false, 'animales' => [], 'total' => 0]);
    }

    $query = Animal::where('estado_vida', Animal::ESTADO_VIVO)
        ->with(['predio', 'lote', 'potrero']);

    if ($tipo === 'lote') {
        $query->where('lote_id', $id);
    } else {
        $query->where('potrero_id', $id);
    }

    if ($user->role->name !== 'admin') {
        $prediosUsuario = $user->predios()->pluck('predios.id')->toArray();
        $query->whereIn('id_predio', $prediosUsuario);
    }

    $animales = $query->get()->map(fn($a) => [
        'id_animal'  => $a->id_animal,
        'codigo'     => $a->codigo,
        'nombre'     => $a->nombre ?? 'Sin nombre',
        'sexo'       => $a->sexo,
        'predio'     => $a->predio->nombre_predio ?? 'N/A',
        'predio_id'  => $a->id_predio,
        'lote'       => $a->lote->nombre ?? null,
        'lote_id'    => $a->lote_id,
        'potrero'    => $a->potrero->nombre ?? null,
        'potrero_id' => $a->potrero_id,
    ]);

    return response()->json([
        'success'  => true,
        'animales' => $animales,
        'total'    => $animales->count(),
    ]);
}

// ─────────────────────────────────────────────────────────────────────────────

/**
 * Ejecuta el traslado masivo.
 * POST /traslado/masivo/ejecutar
 *
 * [API MÓVIL] Este mismo endpoint servirá para la app móvil.
 * Solo hay que agregar una ruta en api.php con middleware sanctum.
 *
 * ──────────────────────────────────────────────────────
 * CAMPOS DEL REQUEST:
 * ──────────────────────────────────────────────────────
 *   origen_tipo           'lote' | 'potrero'
 *   origen_id             ID del lote o potrero de origen
 *
 *   animales_ids[]        IDs de los animales que SÍ se trasladan
 *   animales_excluidos[]  IDs de los que NO se trasladan (opcional)
 *   motivo_exclusion      Requerido si hay excluidos
 *
 *   destino_predio_id     ID del predio destino
 *   destino_lote_id       ID del lote destino (opcional, independiente del potrero)
 *   destino_potrero_id    ID del potrero destino (opcional, independiente del lote)
 *   nombre_lote_nuevo     Nombre del lote a crear en el destino (activa escenario 3)
 *
 *   fecha_movimiento      Fecha del traslado (YYYY-MM-DD)
 *   motivo                Motivo general (opcional)
 *
 * ──────────────────────────────────────────────────────
 * ESCENARIOS:
 * ──────────────────────────────────────────────────────
 *   1 - Mismo predio:
 *       Actualiza lote_id y/o potrero_id. Ambos opcionales e independientes.
 *       Pueden asignarse los dos, uno solo, o ninguno.
 *
 *   2 - Otro predio, sin lote nuevo:
 *       Cambia predio_id. Asigna potrero si viene. lote_id queda NULL.
 *       El origen queda vacío con historial intacto.
 *
 *   3 - Otro predio, con lote nuevo:
 *       Crea un Lote nuevo en el predio destino (nombre_lote_nuevo).
 *       Asigna potrero si también viene destino_potrero_id.
 *       Ambos —lote nuevo y potrero— se asignan al animal.
 */
public function trasladoMasivoStore(Request $request)
{
    $request->validate([
        'origen_tipo'          => 'required|in:lote,potrero',
        'origen_id'            => 'required|integer',

        'animales_ids'         => 'required|array|min:1',
        'animales_ids.*'       => 'integer',
        'animales_excluidos'   => 'nullable|array',
        'animales_excluidos.*' => 'integer',
        'motivo_exclusion'     => 'required_with:animales_excluidos|nullable|string|max:500',

        'destino_predio_id'    => 'required|exists:predios,id',
        'destino_lote_id'      => 'nullable|integer',    // lote existente (escenario 1)
        'destino_potrero_id'   => 'nullable|integer',    // potrero destino (todos los escenarios)
        'nombre_lote_nuevo'    => 'nullable|string|max:255', // activa escenario 3

        'fecha_movimiento'     => 'required|date',
        'motivo'               => 'nullable|string|max:500',
    ]);

    $user = Auth::user();

    // ── SEGURIDAD: el usuario debe tener acceso al origen y al destino ──────
    if ($user->role->name !== 'admin') {
        $prediosUsuario = $user->predios()->pluck('predios.id')->toArray();

        if (!in_array((int)$request->destino_predio_id, $prediosUsuario)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso sobre el predio destino.',
            ], 403);
        }

        $origenValido = $request->origen_tipo === 'lote'
            ? Lote::where('id', $request->origen_id)->whereIn('predio_id', $prediosUsuario)->exists()
            : Potrero::where('id', $request->origen_id)->whereIn('predio_id', $prediosUsuario)->exists();

        if (!$origenValido) {
            return response()->json([
                'success' => false,
                'message' => 'El origen seleccionado no te pertenece.',
            ], 403);
        }
    }

    // ── DATOS DEL ORIGEN ─────────────────────────────────────────────────────
    if ($request->origen_tipo === 'lote') {
        $origenModelo   = Lote::findOrFail($request->origen_id);
        $origenNombre   = $origenModelo->nombre;
        $origenPredioId = $origenModelo->predio_id;
    } else {
        $origenModelo   = Potrero::findOrFail($request->origen_id);
        $origenNombre   = $origenModelo->nombre;
        $origenPredioId = $origenModelo->predio_id;
    }

    // ── ANIMALES SELECCIONADOS ────────────────────────────────────────────────
    $animalesSeleccionados = Animal::where('estado_vida', Animal::ESTADO_VIVO)
        ->whereIn('id_animal', $request->animales_ids)
        ->get();

    if ($animalesSeleccionados->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No se encontraron animales válidos para trasladar.',
        ], 422);
    }

    // ── DETERMINAR ESCENARIO ──────────────────────────────────────────────────
    $mismoPredio  = ((int)$origenPredioId === (int)$request->destino_predio_id);
    $quiereLote   = !empty(trim($request->nombre_lote_nuevo ?? ''));

    // Escenario 1: mismo predio       → usa lote y/o potrero como vienen
    // Escenario 2: otro predio sin lote → lote_id = NULL, potrero si hay
    // Escenario 3: otro predio con lote → crea lote nuevo + potrero si hay
    if ($mismoPredio) {
        $escenario = 1;
    } elseif (!$mismoPredio && !$quiereLote) {
        $escenario = 2;
    } else {
        $escenario = 3;
    }

    // ── PREPARAR IDs DE DESTINO ───────────────────────────────────────────────
    $destino_lote_id    = null;
    $destino_potrero_id = null;
    $loteNuevoCreado    = null;

    if ($escenario === 1) {
        // Mismo predio: lote y potrero son independientes, ambos opcionales
        $destino_lote_id    = $request->destino_lote_id    ?: null;
        $destino_potrero_id = $request->destino_potrero_id ?: null;

    } elseif ($escenario === 2) {
        // Otro predio sin lote: sin lote, potrero opcional
        $destino_lote_id    = null;
        $destino_potrero_id = $request->destino_potrero_id ?: null;

    } elseif ($escenario === 3) {
        // Otro predio con lote: crear lote nuevo, potrero opcional
        $nombreLoteNuevo = trim($request->nombre_lote_nuevo)
            ?: $origenNombre . ' - traslado ' . \Carbon\Carbon::parse($request->fecha_movimiento)->format('d/m/Y');

        $loteNuevoCreado = Lote::create([
            'predio_id' => $request->destino_predio_id,
            'nombre'    => $nombreLoteNuevo,
        ]);

        $destino_lote_id    = $loteNuevoCreado->id;
        $destino_potrero_id = $request->destino_potrero_id ?: null;
    }

    // ── CÓDIGO ÚNICO DEL EVENTO ───────────────────────────────────────────────
    // Agrupa los movimientos y las exclusiones del mismo traslado masivo
    $trasladoRef = 'TM-' . now()->format('YmdHis') . '-' . $user->id;

    $trasladados = 0;

    \DB::beginTransaction();
    try {

        // ── 1. MOVER LOS ANIMALES SELECCIONADOS ──────────────────────────────
        foreach ($animalesSeleccionados as $animal) {

            // Capturar origen ANTES de actualizar
            $origen_predio_id  = $animal->id_predio;
            $origen_lote_id    = $animal->lote_id;
            $origen_potrero_id = $animal->potrero_id;

            // Actualizar ubicación del animal
            $animal->update([
                'id_predio'  => $request->destino_predio_id,
                'lote_id'    => $destino_lote_id,
                'potrero_id' => $destino_potrero_id,
            ]);

            // Registrar movimiento completo (origen + destino)
            \DB::table('movimientos_animales')->insert([
                'animal_id'          => $animal->id_animal,

                // DE DÓNDE venía
                'predio_origen_id'   => $origen_predio_id,
                'lote_origen_id'     => $origen_lote_id,
                'potrero_origen_id'  => $origen_potrero_id,

                // A DÓNDE fue
                'predio_id'          => $request->destino_predio_id,
                'lote_id'            => $destino_lote_id,
                'potrero_id'         => $destino_potrero_id,

                // Lote auto-creado (solo escenario 3)
                'lote_nuevo_id'      => $loteNuevoCreado?->id,

                'tipo_traslado'      => 'masivo',
                'motivo'             => $request->motivo
                                        ?? "Traslado masivo desde {$request->origen_tipo}: {$origenNombre}",
                'fecha_movimiento'   => $request->fecha_movimiento,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            $trasladados++;
        }

        // ── 2. REGISTRAR ANIMALES EXCLUIDOS ──────────────────────────────────
        $excluidos = 0;

        if (!empty($request->animales_excluidos)) {
            $animalesExcluidos = Animal::whereIn('id_animal', $request->animales_excluidos)->get();

            foreach ($animalesExcluidos as $exc) {
                \DB::table('traslado_exclusiones')->insert([
                    'animal_id'    => $exc->id_animal,
                    'traslado_ref' => $trasladoRef,

                    // Dónde QUEDÓ (no se movió, conserva ubicación actual)
                    'predio_id'    => $exc->id_predio,
                    'lote_id'      => $exc->lote_id,
                    'potrero_id'   => $exc->potrero_id,

                    'motivo'       => $request->motivo_exclusion ?? 'Sin motivo especificado',
                    'fecha'        => $request->fecha_movimiento,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
                $excluidos++;
            }
        }

        \DB::commit();

        // ── RESPUESTA ─────────────────────────────────────────────────────────
        $msgEscenario = match($escenario) {
            1 => 'Traslado dentro del mismo predio.',
            2 => 'Animales llegaron al nuevo predio sin lote asignado.',
            3 => "Se creó el lote \"{$loteNuevoCreado->nombre}\" en el predio destino.",
        };

        $msgExcluidos = $excluidos > 0
            ? " {$excluidos} animal(es) quedaron en origen (registrados con motivo)."
            : '';

        return response()->json([
            'success'      => true,
            'message'      => "Se trasladaron {$trasladados} animales. {$msgEscenario}{$msgExcluidos}",
            'trasladados'  => $trasladados,
            'excluidos'    => $excluidos,
            'escenario'    => $escenario,
            'traslado_ref' => $trasladoRef,
            'lote_nuevo'   => $loteNuevoCreado
                ? ['id' => $loteNuevoCreado->id, 'nombre' => $loteNuevoCreado->nombre]
                : null,
        ]);

    } catch (\Exception $e) {
        \DB::rollBack();

        // Si se creó el lote pero algo falló después → borrarlo para no dejar huérfanos
        if ($loteNuevoCreado) {
            $loteNuevoCreado->delete();
        }

        \Log::error('Error en traslado masivo: ' . $e->getMessage(), [
            'user_id'  => $user->id,
            'request'  => $request->except('_token'),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error al realizar el traslado: ' . $e->getMessage(),
        ], 500);
    }
}

private function resolverUsuario(Request $request)
{
    if ($request->filled('user_id')) {
        return \App\Models\User::findOrFail($request->user_id);
    }
    return Auth::user();
}

// ── trasladoMasivoData ────────────────────────────────────────────────────────
// GET /api/traslado/masivo/datos?user_id=5
public function trasladoMasivoData(Request $request)
{
    $user      = $this->resolverUsuario($request);
    $esAdmin   = $user->role->name === 'admin';

    $predios   = $esAdmin
        ? Predios::orderBy('nombre_predio')->get()
        : $user->predios()->orderBy('nombre_predio')->get();

    $predioIds = $predios->pluck('id');

    $lotes = Lote::whereIn('predio_id', $predioIds)
        ->withCount(['animales' => fn($q) => $q->where('estado_vida', Animal::ESTADO_VIVO)])
        ->with('predio')
        ->orderBy('nombre')
        ->get()
        ->map(fn($l) => [
            'id'             => $l->id,
            'nombre'         => $l->nombre,
            'predio_id'      => $l->predio_id,
            'predio_nombre'  => $l->predio->nombre_predio ?? null,
            'total_animales' => $l->animales_count,
        ]);

    $potreros = Potrero::whereIn('predio_id', $predioIds)
        ->withCount(['animales' => fn($q) => $q->where('estado_vida', Animal::ESTADO_VIVO)])
        ->with('predio')
        ->orderBy('nombre')
        ->get()
        ->map(fn($p) => [
            'id'             => $p->id,
            'nombre'         => $p->nombre,
            'predio_id'      => $p->predio_id,
            'predio_nombre'  => $p->predio->nombre_predio ?? null,
            'area'           => $p->area ?? null,
            'total_animales' => $p->animales_count,
        ]);

    return response()->json([
        'success'  => true,
        'predios'  => $predios->map(fn($p) => [
            'id'     => $p->id,
            'nombre' => $p->nombre_predio,
        ]),
        'lotes'    => $lotes,
        'potreros' => $potreros,
    ]);
}

// ── reporteTrasladosListado ───────────────────────────────────────────────────
// Web:  GET /reportes/traslados           → retorna vista Blade
// API:  GET /api/reportes/traslados?user_id=5&tipo_traslado=masivo → retorna JSON
public function reporteTrasladosListado(Request $request)
{
    $user      = $this->resolverUsuario($request);
    $esAdmin   = $user->role->name === 'admin';

    $predios   = $esAdmin
        ? Predios::orderBy('nombre_predio')->get()
        : $user->predios()->orderBy('nombre_predio')->get();

    $predioIds = $predios->pluck('id');

    $query = \DB::table('movimientos_animales as ma')
        ->join('animales as a',       'a.id_animal', '=', 'ma.animal_id')
        ->leftJoin('predios as pd',   'pd.id',       '=', 'ma.predio_id')
        ->leftJoin('lotes as ld',     'ld.id',       '=', 'ma.lote_id')
        ->leftJoin('potreros as pod', 'pod.id',      '=', 'ma.potrero_id')
        ->leftJoin('predios as po',   'po.id',       '=', 'ma.predio_origen_id')
        ->leftJoin('lotes as lo',     'lo.id',       '=', 'ma.lote_origen_id')
        ->leftJoin('potreros as poo', 'poo.id',      '=', 'ma.potrero_origen_id')
        ->leftJoin('lotes as ln',     'ln.id',       '=', 'ma.lote_nuevo_id')
        ->select([
            'ma.id', 'ma.animal_id', 'ma.fecha_movimiento', 'ma.motivo',
            'ma.tipo_traslado', 'ma.created_at',
            'a.codigo as animal_codigo', 'a.nombre as animal_nombre', 'a.sexo as animal_sexo',
            'ma.predio_id', 'ma.lote_id', 'ma.potrero_id',
            'pd.nombre_predio as destino_predio', 'ld.nombre as destino_lote', 'pod.nombre as destino_potrero',
            'ma.predio_origen_id', 'ma.lote_origen_id', 'ma.potrero_origen_id',
            'po.nombre_predio as origen_predio', 'lo.nombre as origen_lote', 'poo.nombre as origen_potrero',
            'ma.lote_nuevo_id', 'ln.nombre as lote_nuevo_nombre',
        ])
        ->whereIn('ma.predio_id', $predioIds)
        ->orderByDesc('ma.fecha_movimiento')
        ->orderByDesc('ma.id');

    if ($request->filled('fecha_desde'))   $query->where('ma.fecha_movimiento', '>=', $request->fecha_desde);
    if ($request->filled('fecha_hasta'))   $query->where('ma.fecha_movimiento', '<=', $request->fecha_hasta);
    if ($request->filled('predio_id'))     $query->where('ma.predio_id', $request->predio_id);
    if ($request->filled('tipo_traslado')) $query->where('ma.tipo_traslado', $request->tipo_traslado);
    if ($request->filled('lote_id')) {
        $query->where(fn($q) => $q->where('ma.lote_id', $request->lote_id)
                                   ->orWhere('ma.lote_origen_id', $request->lote_id));
    }
    if ($request->filled('potrero_id')) {
        $query->where(fn($q) => $q->where('ma.potrero_id', $request->potrero_id)
                                   ->orWhere('ma.potrero_origen_id', $request->potrero_id));
    }
    if ($request->filled('animal_busq')) {
        $t = '%' . $request->animal_busq . '%';
        $query->where(fn($q) => $q->where('a.codigo', 'like', $t)->orWhere('a.nombre', 'like', $t));
    }

    $perPage     = (int)($request->per_page ?? 20);
    $movimientos = $query->paginate($perPage)->withQueryString();

    $stats = \DB::table('movimientos_animales as ma')
        ->whereIn('ma.predio_id', $predioIds)
        ->when($request->filled('fecha_desde'),   fn($q) => $q->where('ma.fecha_movimiento', '>=', $request->fecha_desde))
        ->when($request->filled('fecha_hasta'),   fn($q) => $q->where('ma.fecha_movimiento', '<=', $request->fecha_hasta))
        ->when($request->filled('predio_id'),     fn($q) => $q->where('ma.predio_id', $request->predio_id))
        ->when($request->filled('tipo_traslado'), fn($q) => $q->where('ma.tipo_traslado', $request->tipo_traslado))
        ->selectRaw("
            COUNT(*) as total,
            SUM(tipo_traslado = 'masivo') as total_masivos,
            SUM(tipo_traslado = 'individual' OR tipo_traslado IS NULL) as total_individuales,
            COUNT(DISTINCT animal_id) as total_animales_movidos,
            SUM(lote_nuevo_id IS NOT NULL) as lotes_creados
        ")
        ->first();

    $totalExcluidos = \DB::table('traslado_exclusiones')
        ->whereIn('predio_id', $predioIds)
        ->when($request->filled('fecha_desde'), fn($q) => $q->where('fecha', '>=', $request->fecha_desde))
        ->when($request->filled('fecha_hasta'), fn($q) => $q->where('fecha', '<=', $request->fecha_hasta))
        ->count();

    // Si viene con user_id → es la app móvil → JSON
    if ($request->filled('user_id')) {
        return response()->json([
            'success'         => true,
            'data'            => $movimientos->items(),
            'current_page'    => $movimientos->currentPage(),
            'last_page'       => $movimientos->lastPage(),
            'total'           => $movimientos->total(),
            'per_page'        => $movimientos->perPage(),
            'stats'           => $stats,
            'total_excluidos' => $totalExcluidos,
        ]);
    }

    // Web normal → Blade
    $lotes    = Lote::whereIn('predio_id', $predioIds)->orderBy('nombre')->get(['id', 'nombre', 'predio_id']);
    $potreros = Potrero::whereIn('predio_id', $predioIds)->orderBy('nombre')->get(['id', 'nombre', 'predio_id']);

    return view('reportes.traslados', compact(
        'predios', 'lotes', 'potreros',
        'movimientos', 'stats', 'totalExcluidos', 'perPage'
    ));
}

/**
 * [API MÓVIL] Preview de animales del origen.
 * GET /api/traslado/masivo/preview?tipo=lote&id=5&user_id=3
 *
 * Copia exacta de trasladoMasivoPreview() pero resuelve
 * el usuario por user_id en lugar de la sesión web.
 */
public function apiTrasladoMasivoPreview(Request $request)
{
    // ── Resolver usuario por user_id ──────────────────────────
    if (!$request->filled('user_id')) {
        return response()->json(['success' => false, 'message' => 'user_id requerido.'], 400);
    }
    $user = \App\Models\User::findOrFail($request->user_id);

    // ── Resto idéntico al método web ──────────────────────────
    $tipo = $request->tipo;
    $id   = $request->id;

    if (!$tipo || !$id) {
        return response()->json(['success' => false, 'animales' => [], 'total' => 0]);
    }

    $query = Animal::where('estado_vida', Animal::ESTADO_VIVO)
        ->with(['predio', 'lote', 'potrero']);

    if ($tipo === 'lote') {
        $query->where('lote_id', $id);
    } else {
        $query->where('potrero_id', $id);
    }

    if ($user->role->name !== 'admin') {
        $prediosUsuario = $user->predios()->pluck('predios.id')->toArray();
        $query->whereIn('id_predio', $prediosUsuario);
    }

    $animales = $query->get()->map(fn($a) => [
        'id_animal'  => $a->id_animal,
        'codigo'     => $a->codigo,
        'nombre'     => $a->nombre ?? 'Sin nombre',
        'sexo'       => $a->sexo,
        'predio'     => $a->predio->nombre_predio ?? 'N/A',
        'predio_id'  => $a->id_predio,
        'lote'       => $a->lote->nombre ?? null,
        'lote_id'    => $a->lote_id,
        'potrero'    => $a->potrero->nombre ?? null,
        'potrero_id' => $a->potrero_id,
    ]);

    return response()->json([
        'success'  => true,
        'animales' => $animales,
        'total'    => $animales->count(),
    ]);
}


/**
 * [API MÓVIL] Ejecutar traslado masivo.
 * POST /api/traslado/masivo/ejecutar
 * Body JSON incluye user_id además de todos los campos normales.
 *
 * Copia exacta de trasladoMasivoStore() pero resuelve
 * el usuario por user_id en lugar de la sesión web.
 */
public function apiTrasladoMasivoStore(Request $request)
{
    // ── Resolver usuario por user_id ──────────────────────────
    if (!$request->filled('user_id')) {
        return response()->json(['success' => false, 'message' => 'user_id requerido.'], 400);
    }
    $user = \App\Models\User::findOrFail($request->user_id);

    // ── Validación idéntica al método web ─────────────────────
    $request->validate([
        'origen_tipo'          => 'required|in:lote,potrero',
        'origen_id'            => 'required|integer',
        'animales_ids'         => 'required|array|min:1',
        'animales_ids.*'       => 'integer',
        'animales_excluidos'   => 'nullable|array',
        'animales_excluidos.*' => 'integer',
        'motivo_exclusion'     => 'required_with:animales_excluidos|nullable|string|max:500',
        'destino_predio_id'    => 'required|exists:predios,id',
        'destino_lote_id'      => 'nullable|integer',
        'destino_potrero_id'   => 'nullable|integer',
        'nombre_lote_nuevo'    => 'nullable|string|max:255',
        'fecha_movimiento'     => 'required|date',
        'motivo'               => 'nullable|string|max:500',
    ]);

    // ── Seguridad ─────────────────────────────────────────────
    if ($user->role->name !== 'admin') {
        $prediosUsuario = $user->predios()->pluck('predios.id')->toArray();

        if (!in_array((int)$request->destino_predio_id, $prediosUsuario)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso sobre el predio destino.',
            ], 403);
        }

        $origenValido = $request->origen_tipo === 'lote'
            ? Lote::where('id', $request->origen_id)->whereIn('predio_id', $prediosUsuario)->exists()
            : Potrero::where('id', $request->origen_id)->whereIn('predio_id', $prediosUsuario)->exists();

        if (!$origenValido) {
            return response()->json([
                'success' => false,
                'message' => 'El origen seleccionado no te pertenece.',
            ], 403);
        }
    }

    // ── Datos del origen ──────────────────────────────────────
    if ($request->origen_tipo === 'lote') {
        $origenModelo   = Lote::findOrFail($request->origen_id);
    } else {
        $origenModelo   = Potrero::findOrFail($request->origen_id);
    }
    $origenNombre   = $origenModelo->nombre;
    $origenPredioId = $origenModelo->predio_id;

    // ── Animales seleccionados ────────────────────────────────
    $animalesSeleccionados = Animal::where('estado_vida', Animal::ESTADO_VIVO)
        ->whereIn('id_animal', $request->animales_ids)
        ->get();

    if ($animalesSeleccionados->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No se encontraron animales válidos para trasladar.',
        ], 422);
    }

    // ── Escenario ─────────────────────────────────────────────
    $mismoPredio = ((int)$origenPredioId === (int)$request->destino_predio_id);
    $quiereLote  = !empty(trim($request->nombre_lote_nuevo ?? ''));

    if ($mismoPredio)              $escenario = 1;
    elseif (!$mismoPredio && !$quiereLote) $escenario = 2;
    else                           $escenario = 3;

    // ── Destino ───────────────────────────────────────────────
    $destino_lote_id    = null;
    $destino_potrero_id = null;
    $loteNuevoCreado    = null;

    if ($escenario === 1) {
        $destino_lote_id    = $request->destino_lote_id    ?: null;
        $destino_potrero_id = $request->destino_potrero_id ?: null;
    } elseif ($escenario === 2) {
        $destino_lote_id    = null;
        $destino_potrero_id = $request->destino_potrero_id ?: null;
    } elseif ($escenario === 3) {
        $nombreLoteNuevo = trim($request->nombre_lote_nuevo)
            ?: $origenNombre . ' - traslado ' . \Carbon\Carbon::parse($request->fecha_movimiento)->format('d/m/Y');

        $loteNuevoCreado = Lote::create([
            'predio_id' => $request->destino_predio_id,
            'nombre'    => $nombreLoteNuevo,
        ]);

        $destino_lote_id    = $loteNuevoCreado->id;
        $destino_potrero_id = $request->destino_potrero_id ?: null;
    }

    $trasladoRef = 'TM-' . now()->format('YmdHis') . '-' . $user->id;
    $trasladados = 0;

    \DB::beginTransaction();
    try {
        // 1. Mover animales
        foreach ($animalesSeleccionados as $animal) {
            $origen_predio_id  = $animal->id_predio;
            $origen_lote_id    = $animal->lote_id;
            $origen_potrero_id = $animal->potrero_id;

            $animal->update([
                'id_predio'  => $request->destino_predio_id,
                'lote_id'    => $destino_lote_id,
                'potrero_id' => $destino_potrero_id,
            ]);

            \DB::table('movimientos_animales')->insert([
                'animal_id'         => $animal->id_animal,
                'predio_origen_id'  => $origen_predio_id,
                'lote_origen_id'    => $origen_lote_id,
                'potrero_origen_id' => $origen_potrero_id,
                'predio_id'         => $request->destino_predio_id,
                'lote_id'           => $destino_lote_id,
                'potrero_id'        => $destino_potrero_id,
                'lote_nuevo_id'     => $loteNuevoCreado?->id,
                'tipo_traslado'     => 'masivo',
                'motivo'            => $request->motivo
                                       ?? "Traslado masivo desde {$request->origen_tipo}: {$origenNombre}",
                'fecha_movimiento'  => $request->fecha_movimiento,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
            $trasladados++;
        }

        // 2. Registrar excluidos
        $excluidos = 0;
        if (!empty($request->animales_excluidos)) {
            $animalesExcluidos = Animal::whereIn('id_animal', $request->animales_excluidos)->get();
            foreach ($animalesExcluidos as $exc) {
                \DB::table('traslado_exclusiones')->insert([
                    'animal_id'    => $exc->id_animal,
                    'traslado_ref' => $trasladoRef,
                    'predio_id'    => $exc->id_predio,
                    'lote_id'      => $exc->lote_id,
                    'potrero_id'   => $exc->potrero_id,
                    'motivo'       => $request->motivo_exclusion ?? 'Sin motivo especificado',
                    'fecha'        => $request->fecha_movimiento,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
                $excluidos++;
            }
        }

        \DB::commit();

        $msgEscenario = match($escenario) {
            1 => 'Traslado dentro del mismo predio.',
            2 => 'Animales llegaron al nuevo predio sin lote asignado.',
            3 => "Se creó el lote \"{$loteNuevoCreado->nombre}\" en el predio destino.",
        };

        return response()->json([
            'success'      => true,
            'message'      => "Se trasladaron {$trasladados} animales. {$msgEscenario}",
            'trasladados'  => $trasladados,
            'excluidos'    => $excluidos,
            'escenario'    => $escenario,
            'traslado_ref' => $trasladoRef,
            'lote_nuevo'   => $loteNuevoCreado
                ? ['id' => $loteNuevoCreado->id, 'nombre' => $loteNuevoCreado->nombre]
                : null,
        ]);

    } catch (\Exception $e) {
        \DB::rollBack();
        if ($loteNuevoCreado) $loteNuevoCreado->delete();

        \Log::error('Error en apiTrasladoMasivoStore: ' . $e->getMessage(), [
            'user_id' => $user->id,
            'request' => $request->except('_token'),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error al realizar el traslado: ' . $e->getMessage(),
        ], 500);
    }
}
}
