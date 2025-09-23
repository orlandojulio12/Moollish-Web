<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumo;
use App\Models\AplicacionInsumo;
use App\Models\TipoUsoInsumo;
use App\Models\Animal;
use App\Models\Potrero;
use App\Models\Lote;
use App\Models\MovimientoInsumo;
use App\Models\Predios;
use App\Models\CategoriaInsumo;
use App\Models\InventarioInsumo;
use App\Models\Movimientos;
use App\Models\PlanCuenta;
use App\Models\Medicacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AplicacionInsumosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $aplicaciones = AplicacionInsumo::with(['insumo', 'animal', 'potrero', 'lote', 'tipoUso', 'responsable'])
            ->orderBy('fecha_aplicacion', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('inventario_animales.aplicaciones', compact('user', 'aplicaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $insumos = Insumo::where('activo', true)
            ->orderBy('nombre_comercial')
            ->get();

        return view('inventario_animales.crear_aplicacion', compact('user', 'insumos'));
    }

    /**
     * Obtener tipos de uso para un insumo específico
     */
    public function getTiposUsoPorInsumo($insumoId)
    {
        $insumo = Insumo::with('usos.tipoUso')->findOrFail($insumoId);
        $tiposUso = $insumo->usos->pluck('tipoUso');

        return response()->json($tiposUso);
    }

    /**
     * Obtener animales por predio
     */
    public function getAnimalesPorPredio($predioId)
    {
        $animales = Animal::where('predio_id', $predioId)
            ->orderBy('identificacion')
            ->get();

        return response()->json($animales);
    }

    /**
     * Obtener potreros por predio
     */
    public function getPotrerosPorPredio($predioId)
    {
        try {
            $potreros = Potrero::where('predio_id', $predioId)
                ->orderBy('nombre')
                ->get();

            return response()->json($potreros);

        } catch (\Exception $e) {
            Log::error('Error al obtener potreros por predio', [
                'error' => $e->getMessage(),
                'predio_id' => $predioId
            ]);

            return response()->json([], 500);
        }
    }

    /**
     * Obtener lotes por predio
     */
    public function getLotesPorPredio($predioId)
    {
        try {
            $lotes = Lote::where('predio_id', $predioId)
                ->orderBy('nombre')
                ->get();

            return response()->json($lotes);

        } catch (\Exception $e) {
            Log::error('Error al obtener lotes por predio', [
                'error' => $e->getMessage(),
                'predio_id' => $predioId
            ]);

            return response()->json([], 500);
        }
    }

    /**
     * Show the form for creating a new salida
     */
    public function salidaForm()
    {
        $user = Auth::user();

        // Obtener los predios disponibles para el usuario actual
      /*   $predios = Predios::where(function($query) use ($user) {
            if ($user->role->id !== 1) { // Si no es admin
                $query->where('user_id', $user->id);
            }
        })->where('activo', true)->get(); */
        $predios = $user->predios;
        // Obtener todos los animales activos
        $animales = Animal::filtrarPorEstadoYPredio($user)->get();

        return view('inventario_animales.salidaInsumos', compact('user', 'predios', 'animales'));
    }

    /**
     * Obtener insumos por predio
     */
    public function getInsumosPorPredio($predioId)
    {
        try {
            // Registrar información de depuración
            Log::info('Iniciando getInsumosPorPredio', [
                'predio_id' => $predioId
            ]);

            // Obtenemos los insumos del predio con su inventario
            // Importante: El predio_id está en la tabla insumos, no en inventario_insumos
            $inventario = InventarioInsumo::whereHas('insumo', function($query) use ($predioId) {
                $query->where('predio_id', $predioId);
            })
            ->where('cantidad', '>', 0)
            ->with(['insumo.categoria'])
            ->get();

            // Registrar información de depuración
            Log::info('Resultados de inventario', [
                'count' => $inventario->count()
            ]);

            // Agrupamos por insumo para calcular el stock total
            $insumos = [];
            foreach ($inventario as $item) {
                // Comprobar si el insumo existe
                if (!$item->insumo) {
                    Log::warning('Insumo no encontrado', [
                        'inventario_id' => $item->id,
                        'insumo_id' => $item->insumo_id
                    ]);
                    continue;
                }

                $insumoId = $item->insumo_id;

                if (!isset($insumos[$insumoId])) {
                    $insumos[$insumoId] = [
                        'id' => $item->insumo->id,
                        'codigo' => $item->insumo->codigo,
                        'nombre' => $item->insumo->nombre_comercial,
                        'categoria' => $item->insumo->categoria ? $item->insumo->categoria->nombre : 'Sin categoría',
                        'unidad_medida' => $item->insumo->unidad_medida,
                        'stock' => 0
                    ];
                }

                $insumos[$insumoId]['stock'] += $item->cantidad;
            }

            Log::info('Finalizando getInsumosPorPredio', [
                'insumos_count' => count($insumos)
            ]);

            return response()->json(array_values($insumos));

        } catch (\Exception $e) {
            Log::error('Error al obtener insumos por predio', [
                'error' => $e->getMessage(),
                'predio_id' => $predioId,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener tipos de uso para un insumo específico
     */
    public function getTiposUsoInsumo($insumoId)
    {
        $insumo = Insumo::with(['categoria', 'usos.tipoUso'])->findOrFail($insumoId);
        $tiposUso = [];

        foreach ($insumo->usos as $uso) {
            if ($uso->tipoUso) {
                $tiposUso[] = $uso->tipoUso;
            }
        }

        return response()->json($tiposUso);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'insumo_id' => 'required|exists:insumos,id',
            'tipo_aplicacion' => 'required|in:animal,potrero,lote',
            'animal_id' => 'required_if:tipo_aplicacion,animal|exists:animal,id|nullable',
            'potrero_id' => 'required_if:tipo_aplicacion,potrero|exists:potrero,id|nullable',
            'lote_id' => 'required_if:tipo_aplicacion,lote|exists:lote,id|nullable',
            'cantidad_aplicada' => 'required|numeric|min:0.01',
            'fecha_aplicacion' => 'required|date',
            'hora_aplicacion' => 'nullable',
            'via_administracion' => 'nullable|string|max:100',
            'tipo_uso_id' => 'required|exists:tipos_usos_insumos,id',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $insumo = Insumo::findOrFail($request->insumo_id);

            // Verificar stock suficiente
            $stockActual = $insumo->stockActual();
            if ($stockActual < $request->cantidad_aplicada) {
                return back()->with('error', 'Stock insuficiente. Stock actual: ' . $stockActual)->withInput();
            }

            // Registrar la aplicación
            $aplicacion = new AplicacionInsumo();
            $aplicacion->insumo_id = $request->insumo_id;

            // Asignar según el tipo de aplicación
            if ($request->tipo_aplicacion === 'animal') {
                $aplicacion->animal_id = $request->animal_id;
            } elseif ($request->tipo_aplicacion === 'potrero') {
                $aplicacion->potrero_id = $request->potrero_id;
            } elseif ($request->tipo_aplicacion === 'lote') {
                $aplicacion->lote_id = $request->lote_id;
            }

            $aplicacion->cantidad_aplicada = $request->cantidad_aplicada;
            $aplicacion->fecha_aplicacion = $request->fecha_aplicacion;
            $aplicacion->hora_aplicacion = $request->hora_aplicacion;
            $aplicacion->via_administracion = $request->via_administracion;
            $aplicacion->tipo_uso_id = $request->tipo_uso_id;
            $aplicacion->responsable_id = Auth::id();
            $aplicacion->observaciones = $request->observaciones;
            $aplicacion->save();

            // Registrar el movimiento de salida en inventario
            $movimiento = new MovimientoInsumo();
            $movimiento->insumo_id = $request->insumo_id;
            $movimiento->tipo_movimiento = 'salida';
            $movimiento->cantidad = $request->cantidad_aplicada;
            $movimiento->fecha_movimiento = $request->fecha_aplicacion;
            $movimiento->motivo = 'Aplicación: ' . $request->tipo_aplicacion . ' - ' . $request->observaciones;
            $movimiento->predio_id = $insumo->predio_id;
            $movimiento->created_by = Auth::id();
            $movimiento->save();

            DB::commit();

            return redirect()->route('aplicaciones.index')
                ->with('success', 'Aplicación registrada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar la aplicación: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $aplicacion = AplicacionInsumo::with(['insumo', 'animal', 'potrero', 'lote', 'tipoUso', 'responsable'])
            ->findOrFail($id);

        return view('inventario_animales.ver_aplicacion', compact('user', 'aplicacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // No se permite editar una aplicación, solo verla
        return redirect()->route('aplicaciones.show', $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // No se permite actualizar una aplicación
        return redirect()->route('aplicaciones.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // No se permite eliminar una aplicación
        return redirect()->route('aplicaciones.index');
    }

    /**
     * Filtrar aplicaciones por diversos criterios
     */
    public function filtrar(Request $request)
    {
        $user = Auth::user();

        $query = AplicacionInsumo::with(['insumo', 'animal', 'potrero', 'lote', 'tipoUso', 'responsable']);

        // Filtros
        if ($request->has('insumo_id') && $request->insumo_id) {
            $query->where('insumo_id', $request->insumo_id);
        }

        if ($request->has('tipo_aplicacion') && $request->tipo_aplicacion) {
            if ($request->tipo_aplicacion === 'animal') {
                $query->whereNotNull('animal_id');
            } elseif ($request->tipo_aplicacion === 'potrero') {
                $query->whereNotNull('potrero_id');
            } elseif ($request->tipo_aplicacion === 'lote') {
                $query->whereNotNull('lote_id');
            }
        }

        if ($request->has('fecha_desde') && $request->fecha_desde) {
            $query->where('fecha_aplicacion', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta') && $request->fecha_hasta) {
            $query->where('fecha_aplicacion', '<=', $request->fecha_hasta);
        }

        if ($request->has('tipo_uso_id') && $request->tipo_uso_id) {
            $query->where('tipo_uso_id', $request->tipo_uso_id);
        }

        // Ordenar
        $query->orderBy('fecha_aplicacion', 'desc')
            ->orderBy('created_at', 'desc');

        $aplicaciones = $query->paginate(15);

        // Obtener datos para filtros
        $insumos = Insumo::where('activo', true)
            ->orderBy('nombre_comercial')
            ->get();
        $tiposUso = TipoUsoInsumo::orderBy('nombre')->get();

        return view('inventario_animales.aplicaciones', compact('user', 'aplicaciones', 'insumos', 'tiposUso', 'request'));
    }

    /**
     * Store a new salida de insumo
     */
    public function salidaStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'insumo_id' => 'required|exists:insumos,id',
            'tipo_aplicacion' => 'required|in:animal,potrero,lote',
            'animal_id' => 'required_if:tipo_aplicacion,animal|nullable',
            'potrero_id' => 'required_if:tipo_aplicacion,potrero|nullable',
            'lote_id' => 'required_if:tipo_aplicacion,lote|nullable',
            'cantidad_aplicada' => 'required|numeric|min:0.01',
            'fecha_aplicacion' => 'required|date',
            'hora_aplicacion' => 'nullable',
            'via_administracion' => 'nullable|string|max:100',
            'tipo_uso_id' => 'nullable|exists:tipos_usos_insumos,id',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        try {
            // Registrar información en logs para depuración
            Log::info('Iniciando registro de salida de insumo en AplicacionInsumosController', [
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            DB::beginTransaction();

            $insumo = Insumo::with('categoria')->findOrFail($request->insumo_id);
            $cantidadAplicada = (float) $request->cantidad_aplicada;

            // Log de detalles del insumo
            Log::info('Información del insumo seleccionado', [
                'insumo_id' => $insumo->id,
                'nombre' => $insumo->nombre_comercial,
                'categoria_id' => $insumo->categoria_id ?? 'No tiene categoría',
                'categoria_nombre' => $insumo->categoria->nombre ?? 'No tiene categoría',
                'categoria_cargada' => $insumo->relationLoaded('categoria') ? 'Sí' : 'No',
                'stock_actual' => $insumo->stockActual()
            ]);

            // Verificar stock suficiente
            $stockActual = $insumo->stockActual();
            if ($stockActual < $cantidadAplicada) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock insuficiente. Stock actual: ' . $stockActual
                    ], 422);
                }

                return back()->with('error', 'Stock insuficiente. Stock actual: ' . $stockActual)->withInput();
            }

            // Obtener el inventario del insumo
            $inventario = InventarioInsumo::where('insumo_id', $insumo->id)
                ->orderBy('fecha_caducidad', 'asc')
                ->get();

            $cantidadRestante = $cantidadAplicada;
            $costoTotalSalida = 0;

            // Usaremos una copia temporal para no afectar la transacción si falla el cálculo
            $idsInventarioActualizar = [];
            $cantidadesInventarioActualizar = [];

            foreach ($inventario as $item) {
                if ($cantidadRestante <= 0) break;

                if ($item->cantidad > 0) {
                    $cantidadADescontar = min($cantidadRestante, $item->cantidad);

                    // Guardar temporalmente qué actualizar
                    $idsInventarioActualizar[] = $item->id;
                    $cantidadesInventarioActualizar[$item->id] = $item->cantidad - $cantidadADescontar;

                    // Calcular costo de la cantidad descontada
                    $costoDescontado = $cantidadADescontar * $item->costo_unitario;
                    $costoTotalSalida += $costoDescontado;

                    $cantidadRestante -= $cantidadADescontar;

                    Log::info('Cálculo para descontar de lote de inventario (AplicacionInsumo)', [
                        'inventario_id' => $item->id,
                        'cantidad_a_descontar' => $cantidadADescontar,
                        'costo_unitario_lote' => $item->costo_unitario,
                        'costo_parcial_salida' => $costoDescontado,
                        'cantidad_original_lote' => $item->cantidad
                    ]);
                }
            }

            // Verificar si se pudo cubrir toda la cantidad
            if ($cantidadRestante > 0.001) { // Margen pequeño para errores de float
                DB::rollBack(); // Revertir transacción
                Log::critical('Error crítico: Stock insuficiente detectado durante cálculo de costo (AplicacionInsumo). Verificación inicial pudo ser incorrecta.', [
                    'insumo_id' => $insumo->id,
                    'cantidad_solicitada' => $cantidadAplicada,
                    'cantidad_faltante' => $cantidadRestante,
                    'stock_reportado_inicialmente' => $stockActual
                ]);
                // Devolver error claro al usuario/ajax
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => "Error crítico de inventario para '{$insumo->nombre_comercial}'. Contacte soporte."], 422);
                }
                return redirect()->back()
                    ->with('error', "Error crítico de inventario para '{$insumo->nombre_comercial}'. Contacte soporte.")
                    ->withInput();
            }


            Log::info('Costo total de salida calculado (AplicacionInsumo)', ['costo_total' => $costoTotalSalida]);

            // Calcular costo unitario promedio REAL para esta salida
            $costoUnitarioRealSalida = ($cantidadAplicada > 0) ? $costoTotalSalida / $cantidadAplicada : 0;
            Log::info('Costo unitario promedio real calculado para esta salida (AplicacionInsumo)', ['costo_unitario_real' => $costoUnitarioRealSalida]);

            // AHORA SÍ: Actualizar el inventario REALMENTE
            foreach($idsInventarioActualizar as $idInventario) {
                InventarioInsumo::where('id', $idInventario)->update(['cantidad' => $cantidadesInventarioActualizar[$idInventario]]);
                Log::info('Lote de inventario actualizado (AplicacionInsumo)', ['inventario_id' => $idInventario, 'nueva_cantidad' => $cantidadesInventarioActualizar[$idInventario]]);
            }

            // Registrar el movimiento de salida en inventario (AHORA con costo real)
            $movimiento = new MovimientoInsumo();
            $movimiento->insumo_id = $request->insumo_id;
            $movimiento->tipo_movimiento = 'salida';
            $movimiento->cantidad = $cantidadAplicada;
            $movimiento->costo_unitario = $costoUnitarioRealSalida; // Usar el costo real
            $movimiento->fecha_movimiento = $request->fecha_aplicacion;
            $movimiento->motivo = 'Aplicación: ' . $request->tipo_aplicacion;
            $movimiento->predio_id = $insumo->predio_id;
            $movimiento->created_by = Auth::id();
            $movimiento->save();
            Log::info('Movimiento de salida de insumo registrado (AplicacionInsumo con costo real)', [
                    'movimiento_id' => $movimiento->id,
                    'insumo_id' => $insumo->id,
                    'cantidad' => $cantidadAplicada,
                    'costo_unitario_aplicado' => $costoUnitarioRealSalida // Loguear el costo aplicado
                ]);


            // --- INICIO: Lógica de Doble Entrada Contable (replicada de AnimalesController) ---
            if ($costoTotalSalida > 0) { // Use variable from this context

                // 1. Movimiento de Costo/Gasto (Débito)
                $subcuentaCostoId = $insumo->plan_cuenta; // Usar la cuenta definida en el insumo

                if ($subcuentaCostoId) {
                    $movimientoCosto = Movimientos::create([ // Use correct model namespace/import
                        'usuario_id' => Auth::id(),
                        'id_predio' => $insumo->predio_id, // Use predio from insumo
                        'cantidad' => $costoTotalSalida, // Débito
                        'fecha' => $request->fecha_aplicacion, // Use fecha from request
                        'descripcion' => "Costo por salida insumo: {$insumo->nombre_comercial} - Aplicación: {$request->tipo_aplicacion}", // Updated description
                        'plan_cuenta' => $subcuentaCostoId,
                    ]);
                    Log::info('Movimiento Débito (Costo) registrado por salida de insumo', [ // Updated log message
                        'movimiento_id' => $movimientoCosto->id,
                        'cuenta_costo_id' => $subcuentaCostoId,
                        'monto' => $costoTotalSalida
                    ]);
                } else {
                    Log::warning('El insumo no tiene definida una cuenta contable (plan_cuenta) para registrar el costo de salida.', [ // Updated log message
                        'insumo_id' => $insumo->id,
                        'insumo_nombre' => $insumo->nombre_comercial
                    ]);
                }

                // 2. Movimiento de Inventario (Crédito)
                // !! IMPORTANTE: Usar el ID correcto de la cuenta de Activo para 'Inventario de Insumos' !!
                $idCuentaActivoInventario = 88; // <-- Hardcoded value from AnimalesController

                if ($idCuentaActivoInventario) { // This check is technically redundant if hardcoded
                     $movimientoInventario = Movimientos::create([ // Use correct model namespace/import
                        'usuario_id' => Auth::id(),
                        'id_predio' => $insumo->predio_id, // Use predio from insumo
                        'cantidad' => $costoTotalSalida, // Crédito
                        'fecha' => $request->fecha_aplicacion, // Use fecha from request
                        'descripcion' => "Crédito inventario por salida: {$insumo->nombre_comercial}", // Updated description
                        'plan_cuenta' => $idCuentaActivoInventario,
                    ]);
                    Log::info('Movimiento Crédito (Inventario) registrado por salida de insumo', [ // Updated log message
                        'movimiento_id' => $movimientoInventario->id,
                        'cuenta_activo_id' => $idCuentaActivoInventario,
                        'monto' => $costoTotalSalida
                    ]);
                } else { // This else block likely won't be reached with hardcoded ID
                    Log::error("No se encontró la cuenta de Activo 'Inventario de Insumos' (ID: {$idCuentaActivoInventario}) para registrar el crédito por salida.");
                    // Considerar si lanzar una excepción
                }
            } else {
                Log::info('No se registró movimiento económico porque el costo total calculado fue cero.', [
                    'insumo_id' => $insumo->id
                ]);
            }
            // --- FIN: Lógica de Doble Entrada Contable ---

            // --- NUEVA LÓGICA: Registrar Medicación si aplica (copiada y adaptada de InsumosController) ---
            if ($request->tipo_aplicacion === 'animal') {
                Log::info('Salida aplicada a animal, verificando categoría de insumo para medicación automática.', [
                    'insumo_id' => $insumo->id,
                    'categoria_id' => $insumo->categoria_id ?? 'No definida',
                    'categoria_nombre_actual' => $insumo->categoria ? $insumo->categoria->nombre : 'No existe relación'
                ]);

                // Verificar categoría (insensible a mayúsculas/minúsculas)
                $categoriasRelevantes = ['sanidad', 'vacunas'];
                $categoriaCoincideInsensible = $insumo->categoria && in_array(
                    strtolower($insumo->categoria->nombre),
                    array_map('strtolower', $categoriasRelevantes)
                );

                Log::info('Resultado de verificación de categoría (insensible)', [
                    'coincide' => $categoriaCoincideInsensible ? 'Sí' : 'No'
                ]);

                if ($categoriaCoincideInsensible) {
                    Log::info('Insumo pertenece a categoría relevante, creando registro de medicación automático.', ['categoria' => $insumo->categoria->nombre]);

                    try {
                        $medicacion = Medicacion::create([
                            'fecha_medicacion' => $request->fecha_aplicacion,
                            // Usar observaciones como motivo, o un default
                            'motivo' => $request->observaciones ?: "Aplicación de {$insumo->nombre_comercial}",
                            'id_animal' => $request->animal_id,
                            // Dejar veterinario null ya que no se pide en este form
                            'id_veterinario' => null,
                            'id_predio' => $insumo->predio_id, // Usar el predio del insumo o de request
                            'insumo_id' => $insumo->id,
                            'cantidad' => $cantidadAplicada, // Usar la cantidad ya validada y convertida
                            'via_administracion' => $request->via_administracion ?? null,
                            // Usar las mismas observaciones
                            'observacion' => $request->observaciones ?? null,
                        ]);

                        Log::info('Registro de medicación creado automáticamente desde salida de insumo.', [
                            'medicacion_id' => $medicacion->id,
                            'animal_id' => $request->animal_id,
                            'insumo_id' => $insumo->id
                        ]);
                    } catch (\Exception $e) {
                        // Loguear el error pero no detener la transacción principal (la salida ya se hizo)
                        Log::error('Error al crear registro de medicación automático', [
                            'mensaje' => $e->getMessage(),
                            'linea' => $e->getLine(),
                            'archivo' => $e->getFile(),
                            'datos_medicacion' => [
                                'fecha_medicacion' => $request->fecha_aplicacion,
                                'motivo' => $request->observaciones ?: "Aplicación de {$insumo->nombre_comercial}",
                                'id_animal' => $request->animal_id,
                                'id_predio' => $insumo->predio_id,
                                'insumo_id' => $insumo->id,
                                'cantidad' => $cantidadAplicada,
                                'via_administracion' => $request->via_administracion ?? null,
                                'observacion' => $request->observaciones ?? null,
                            ]
                        ]);
                    }
                } else {
                    Log::info('Insumo NO pertenece a Sanidad/Vacunas (insensible), no se crea medicación automática.', [
                        'categoria_actual' => $insumo->categoria->nombre ?? 'No definida',
                    ]);
                }
            }

            DB::commit();

            Log::info('Salida de insumo registrada correctamente', [
                'aplicacion_id' => $movimiento->id,
                'insumo_id' => $insumo->id,
                'cantidad' => $cantidadAplicada
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Salida de insumo registrada correctamente',
                    'redirect' => route('insumos.index')
                ]);
            }

            return redirect()->route('insumos.index')
                ->with('success', 'Salida de insumo registrada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al registrar salida de insumo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar la salida: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error al registrar la salida: ' . $e->getMessage())->withInput();
        }
    }
}
