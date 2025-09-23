<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumo;
use App\Models\CategoriaInsumo;
use App\Models\TipoUsoInsumo;
use App\Models\UsoInsumo;
use App\Models\InventarioInsumo;
use App\Models\MovimientoInsumo;
use App\Models\Predios;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Medicacion;
use App\Models\Animal;
use App\Models\Potrero;
use App\Models\Lote;
use Illuminate\Support\Facades\Log;
class InsumosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $insumos = Insumo::with(['categoria', 'predio'])
            ->where('activo', true)
            ->orderBy('nombre_comercial')
            ->get();

        return view('inventario_animales.insumos', compact('user', 'insumos'));
    }

    /**
     * Show the form for registering a new insumo with the custom form.
     */
    public function registroForm()
    {
        $user = Auth::user();
        $predios = $user->predios;

        // Contar insumos con stock bajo y próximos a caducar para el dashboard si se necesita
        $insumosBajoStock = 0;
        $insumosCaducando = 0;

        // Pasar una colección vacía para categorías
        $categorias = collect();

        // Obtener todas las categorías principales (6 dígitos) y subcuentas (8 dígitos)
        $categoriasPrincipales = \App\Models\PlanCuenta::whereRaw('LENGTH(codcta) = 6')->get();
        $subcuentasDetalles = \App\Models\PlanCuenta::whereRaw('LENGTH(codcta) = 8')->get();

        return view('inventario_animales.registroInsumos', compact('user', 'categorias', 'predios', 'insumosBajoStock', 'insumosCaducando', 'categoriasPrincipales', 'subcuentasDetalles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $categorias = CategoriaInsumo::orderBy('nombre')->get();
        $predios = Predios::orderBy('nombre_predio')->get();
        return view('inventario_animales.crear_insumo', compact('user', 'categorias', 'predios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:50',
            'nombre_comercial' => 'required|string|max:150',
            'nombre_generico' => 'nullable|string|max:150',
            'unidad_medida' => 'required|string|max:50',
            'predio_id' => 'required|exists:predios,id',
            'categoria_id' => 'required',
            'precio_referencia' => 'nullable|numeric|min:0',
            'plan_cuenta' => 'required|exists:plan_cuentas,id',
            'fabricante' => 'nullable|string|max:150',
            'registro_ica' => 'nullable|string|max:100',
            'principio_activo' => 'nullable|string|max:150',
            'tiempo_retiro_leche' => 'nullable|integer|min:0',
            'tiempo_retiro_carne' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
            'tipos_uso_custom.*.nombre' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            // Si es una solicitud AJAX, devolver errores como JSON
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
            \Illuminate\Support\Facades\Log::info('Iniciando registro de insumo', [
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            DB::beginTransaction();

            // Crear el nuevo insumo
            $insumo = new Insumo();
            $insumo->codigo = $request->codigo;
            $insumo->nombre_comercial = $request->nombre_comercial;
            $insumo->nombre_generico = $request->nombre_generico;
            $insumo->unidad_medida = $request->unidad_medida;
            $insumo->predio_id = $request->predio_id;
            $insumo->categoria_id = $request->categoria_id;
            $insumo->precio_referencia = $request->precio_referencia;
            $insumo->plan_cuenta = $request->plan_cuenta;
            $insumo->fabricante = $request->fabricante;
            $insumo->registro_ica = $request->registro_ica;
            $insumo->principio_activo = $request->principio_activo;
            $insumo->tiempo_retiro_leche = $request->tiempo_retiro_leche;
            $insumo->tiempo_retiro_carne = $request->tiempo_retiro_carne;
            $insumo->observaciones = $request->observaciones;
            $insumo->activo = true;
            $insumo->created_by = Auth::id();
            $insumo->save();

            // Procesar los tipos de uso personalizados si existen
            if ($request->has('tipos_uso_custom') && is_array($request->tipos_uso_custom)) {
                foreach ($request->tipos_uso_custom as $tipoUso) {
                    if (!empty($tipoUso['nombre'])) {
                        $usoInsumo = new UsoInsumo();
                        $usoInsumo->insumo_id = $insumo->id;
                        $usoInsumo->nombre_personalizado = $tipoUso['nombre'];
                        $usoInsumo->save();

                        \Illuminate\Support\Facades\Log::info('Uso personalizado registrado', [
                            'insumo_id' => $insumo->id,
                            'nombre_uso' => $tipoUso['nombre']
                        ]);
                    }
                }
            }

            DB::commit();

            \Illuminate\Support\Facades\Log::info('Insumo registrado correctamente', [
                'insumo_id' => $insumo->id,
                'codigo' => $insumo->codigo,
                'nombre' => $insumo->nombre_comercial
            ]);

            $mensaje = "Insumo '{$request->nombre_comercial}' registrado correctamente.";

            // Responder según el tipo de solicitud
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $mensaje,
                    'insumo' => $insumo,
                    'redirect' => route('insumos.index')
                ]);
            }

            return redirect()->route('insumos.index')
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();

            // Registrar error en logs
            \Illuminate\Support\Facades\Log::error('Error al registrar insumo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Preparar mensaje de error
            $errorMsg = 'Error al registrar el insumo: ' . $e->getMessage();

            // Responder según el tipo de solicitud
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                    'error_details' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }

            return back()->with('error', $errorMsg)->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $user = Auth::user();
        $insumo = Insumo::with(['categoria', 'predio', 'usos']) // Simplificado, podemos añadir más relaciones si el modal las necesita
            ->findOrFail($id);

        // Calcular stock actual (asumiendo que el método existe en el modelo Insumo)
        $stock = $insumo->stockActual();

        // Si la solicitud es AJAX, devolver JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'insumo' => $insumo,
                'stock' => $stock,
                // Cargamos los nombres de los usos aquí si es necesario
                'usos_nombres' => $insumo->usos->pluck('nombre_personalizado')->filter()->implode(', ') ?: 'Ninguno'
            ]);
        }

        // Si no es AJAX, podríamos redirigir a la consulta o mostrar un error, ya que la vista no existe
        // Por ahora, redirigimos a la consulta como fallback.
        return redirect()->route('insumos.consulta')->with('info', 'La vista detallada no está implementada, mostrando consulta general.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $insumo = Insumo::with(['usos'])->findOrFail($id);
        $categorias = CategoriaInsumo::orderBy('nombre')->get();
        $predios = Predios::orderBy('nombre')->get();

        // Obtener los tipos de uso asignados
        $tiposUsoAsignados = $insumo->usos->pluck('tipo_uso_id')->toArray();

        // Obtener los tipos de uso disponibles según la categoría
        $tiposUso = TipoUsoInsumo::where('categoria_insumo_id', $insumo->categoria_id)
            ->orderBy('nombre')
            ->get();

        return view('inventario_animales.editar_insumo', compact('user', 'insumo', 'categorias', 'predios', 'tiposUso', 'tiposUsoAsignados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:50|unique:insumos,codigo,' . $id,
            'nombre_comercial' => 'required|string|max:150',
            'categoria_id' => 'required|exists:categorias_insumos,id',
            'unidad_medida' => 'required|string|max:50',
            'predio_id' => 'required|exists:predios,id',
            'requiere_receta' => 'nullable|boolean',
            'referencia' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $insumo = Insumo::findOrFail($id);
            $insumo->codigo = $request->codigo;
            $insumo->nombre_comercial = $request->nombre_comercial;
            $insumo->categoria_id = $request->categoria_id;
            $insumo->unidad_medida = $request->unidad_medida;
            $insumo->predio_id = $request->predio_id;
            $insumo->requiere_receta = $request->has('requiere_receta');
            $insumo->referencia = $request->referencia;
            $insumo->descripcion = $request->descripcion;
            $insumo->save();

            // Eliminar los usos asignados actuales
            UsoInsumo::where('insumo_id', $insumo->id)->delete();

            // Actualizar los tipos de uso
            if ($request->has('tipos_uso') && is_array($request->tipos_uso)) {
                foreach ($request->tipos_uso as $tipoUsoId) {
                    $usoInsumo = new UsoInsumo();
                    $usoInsumo->insumo_id = $insumo->id;
                    $usoInsumo->tipo_uso_id = $tipoUsoId;
                    $usoInsumo->save();
                }
            }

            DB::commit();

            return redirect()->route('insumos.show', $insumo->id)
                ->with('success', 'Insumo actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el insumo: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $insumo = Insumo::findOrFail($id);

            // Marcar como inactivo en lugar de eliminar
            $insumo->activo = false;
            $insumo->save();

            return redirect()->route('insumos.index')
                ->with('success', 'Insumo desactivado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al desactivar el insumo: ' . $e->getMessage());
        }
    }

    /**
     * Obtener los tipos de uso para una categoría
     */
    public function getTiposUso($categoriaId)
    {
        $tiposUso = TipoUsoInsumo::where('categoria_insumo_id', $categoriaId)
            ->orderBy('nombre')
            ->get();

        return response()->json($tiposUso);
    }

    /**
     * Registrar movimiento de inventario
     */
    public function registrarMovimiento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'insumo_id' => 'required|exists:insumos,id',
            'tipo_movimiento' => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|numeric|min:0.01',
            'costo_unitario' => 'nullable|numeric|min:0',
            'fecha_movimiento' => 'required|date',
            'motivo' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $insumo = Insumo::findOrFail($request->insumo_id);

            // Verificar stock suficiente en caso de salida
            if ($request->tipo_movimiento === 'salida') {
                $stockActual = $insumo->stockActual();
                if ($stockActual < $request->cantidad) {
                    return back()->with('error', 'Stock insuficiente. Stock actual: ' . $stockActual)->withInput();
                }
            }

            $movimiento = new MovimientoInsumo();
            $movimiento->insumo_id = $request->insumo_id;
            $movimiento->tipo_movimiento = $request->tipo_movimiento;
            $movimiento->cantidad = $request->cantidad;
            $movimiento->costo_unitario = $request->costo_unitario;
            $movimiento->fecha_movimiento = $request->fecha_movimiento;
            $movimiento->motivo = $request->motivo;
            $movimiento->predio_id = $insumo->predio_id;
            $movimiento->created_by = Auth::id();
            $movimiento->save();

            // Si es entrada, registrar en inventario
            if ($request->tipo_movimiento === 'entrada') {
                $inventario = new InventarioInsumo();
                $inventario->insumo_id = $request->insumo_id;
                $inventario->cantidad = $request->cantidad;
                $inventario->costo_unitario = $request->costo_unitario ?? 0;
                $inventario->fecha_compra = $request->fecha_movimiento;
                $inventario->fecha_caducidad = $request->fecha_caducidad;
                $inventario->lote = $request->lote;
                $inventario->proveedor = $request->proveedor;
                $inventario->observaciones = $request->motivo;
                $inventario->predio_id = $request->predio_id;
                $inventario->cantidad_original = $request->cantidad;
                $inventario->save();
            }

            DB::commit();

            return redirect()->route('insumos.show', $request->insumo_id)
                ->with('success', 'Movimiento registrado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar el movimiento: ' . $e->getMessage())->withInput();
        }
    }

    public function consultaView()
    {
        $user = Auth::user();
        $userPredioIds = $user->predios->pluck('id')->toArray(); // Obtener IDs de predios del usuario
        $predios = $user->predios; // Pasar la colección normal para el filtro
        $categorias = CategoriaInsumo::orderBy('nombre')->get();

        // 1. Obtener todos los lotes de inventario activos para los predios del usuario
        $inventarioActivo = InventarioInsumo::with(['insumo', 'insumo.categoria', 'predio']) // Cargar relaciones necesarias
            ->whereIn('predio_id', $userPredioIds)
            ->where('cantidad', '>', 0) // Solo lotes con stock
            ->get();

        // 2. Agrupar dos veces y calcular
        $results = []; // Usaremos un array para recolectar
        $groupedByInsumo = $inventarioActivo->groupBy('insumo_id');

        foreach ($groupedByInsumo as $insumoId => $lotesDelInsumo) {
            $groupedByPredio = $lotesDelInsumo->groupBy('predio_id'); // Agrupar por predio dentro del insumo

            foreach ($groupedByPredio as $predioId => $lotesDelInsumoPredio) {
                // Ahora $lotesDelInsumoPredio ES la colección correcta
                $stockActual = $lotesDelInsumoPredio->sum('cantidad');
                $valorTotal = $lotesDelInsumoPredio->sum(function ($lote) {
                    return $lote->cantidad * $lote->costo_unitario;
                });
                $valorUnitario = ($stockActual > 0) ? $valorTotal / $stockActual : 0;

                // Obtener el primer lote para datos comunes
                $primerLote = $lotesDelInsumoPredio->first();
                if (!$primerLote) continue; // Seguridad, aunque no debería pasar

                $insumo = $primerLote->insumo;
                $predio = $primerLote->predio;

                // Añadir al array de resultados
                $results[] = [
                    'insumo_id' => $insumoId,
                    'predio_id' => $predioId,
                    'nombre_comercial' => $insumo ? $insumo->nombre_comercial : 'N/A',
                    'nombre_predio' => $predio ? $predio->nombre_predio : 'N/A',
                    'categoria_id' => $insumo ? $insumo->categoria_id : null,
                    'unidad_medida' => $insumo ? $insumo->unidad_medida : 'N/A',
                    'stock_actual' => $stockActual,
                    'valor_unitario' => $valorUnitario,
                    'valor_total' => $valorTotal,
                    'insumo' => $insumo // Pasar el objeto insumo
                ];
            }
        }

        // 3. Convertir a colección, ordenar y calcular total general
        $insumosPorPredioSorted = collect($results)
                                ->sortBy('nombre_comercial')
                                ->values(); // Resetear keys

        $valorTotalInventario = $insumosPorPredioSorted->sum('valor_total');

        // 4. Asignar número de fila usando map
        $insumosPorPredioNumbered = $insumosPorPredioSorted->map(function ($item, $index) {
            $item['row_number'] = $index + 1; // Add the row number to the item array
            return $item; // Return the modified item
        });

        return view('inventario_animales.consultaInsumos', compact(
            'user',
            'insumosPorPredioNumbered', // <-- Pasar la colección con números de fila
            'predios',          // Pasar colección normal de predios
            'categorias',
            'valorTotalInventario'
        ));
    }

    /**
     * Muestra el formulario para registrar entrada de insumos al inventario.
     */
  public function entradaForm()
    {
        $user = Auth::user();

        // Obtener los predios para el selector
        $predios = $user->predios;
        $predioIds = $predios->pluck('id')->toArray(); // Obtener IDs de los predios del usuario

        // Obtener insumos activos filtrando por los predios del usuario
        $insumos = Insumo::with(['categoria', 'predio'])
            ->whereIn('predio_id', $predioIds) // <-- Filtrar por los IDs de predio
            ->orderBy('nombre_comercial')
            ->get();


        return view('inventario_animales.entradaInsumos', compact('user', 'insumos', 'predios'));
    }

    /**
     * Procesa el registro de entrada de insumos al inventario.
     */
    public function entradaStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_entrada' => 'required|date',
            'proveedor' => 'required|string|max:100',
            'predio_id' => 'required|exists:predios,id',
            'factura_numero' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
            'entradas' => 'required|array|min:1',
            'entradas.*.insumo_id' => 'required|exists:insumos,id',
            'entradas.*.cantidad' => 'required|numeric|min:0.01',
            'entradas.*.precio' => 'required|numeric|min:0',
            'entradas.*.fecha_vencimiento' => 'nullable|date',
            'entradas.*.notas' => 'nullable|string|max:255',
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
            \Illuminate\Support\Facades\Log::info('Iniciando registro de entrada de insumos', [
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            DB::beginTransaction();

            // Procesar cada entrada de insumo
            foreach ($request->entradas as $key => $entrada) {
                // Verificar que el insumo existe
                $insumo = Insumo::findOrFail($entrada['insumo_id']);

                // Registrar el movimiento de inventario
                $movimiento = new MovimientoInsumo();
                $movimiento->insumo_id = $entrada['insumo_id'];
                $movimiento->tipo_movimiento = 'entrada';
                $movimiento->cantidad = $entrada['cantidad'];
                $movimiento->costo_unitario = $entrada['precio'];
                $movimiento->fecha_movimiento = $request->fecha_entrada;
                $movimiento->motivo = "Entrada de insumo. Factura: {$request->factura_numero}";
                $movimiento->predio_id = $request->predio_id;
                $movimiento->created_by = Auth::id();
                $movimiento->save();

                // Registrar en la tabla de inventario
                $inventario = new InventarioInsumo();
                $inventario->insumo_id = $entrada['insumo_id'];
                $inventario->cantidad = $entrada['cantidad'];
                $inventario->costo_unitario = $entrada['precio'];
                $inventario->fecha_compra = $request->fecha_entrada;
                $inventario->fecha_caducidad = $entrada['fecha_vencimiento'] ?? null;
                $inventario->lote = null; // Podríamos añadir campo de lote en el futuro
                $inventario->proveedor = $request->proveedor;
                $inventario->observaciones = $entrada['notas'] ?? $request->observaciones;
                $inventario->cantidad_original = $entrada['cantidad'];
                $inventario->predio_id = $request->predio_id;
                $inventario->save();

                // Actualizar el precio de referencia del insumo si es necesario
                if (!$insumo->precio_referencia || $insumo->precio_referencia == 0) {
                    $insumo->precio_referencia = $entrada['precio'];
                    $insumo->save();
                }

                \Illuminate\Support\Facades\Log::info('Entrada de insumo registrada', [
                    'insumo_id' => $entrada['insumo_id'],
                    'cantidad' => $entrada['cantidad'],
                    'costo' => $entrada['precio']
                ]);
            }

            DB::commit();

            $mensaje = "Entrada de insumos registrada correctamente.";

            // Responder según el tipo de solicitud
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $mensaje,
                    'redirect' => route('insumos.index')
                ]);
            }

            return redirect()->route('insumos.index')
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();

            // Registrar error en logs
            \Illuminate\Support\Facades\Log::error('Error al registrar entrada de insumos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Preparar mensaje de error
            $errorMsg = 'Error al registrar la entrada de insumos: ' . $e->getMessage();

            // Responder según el tipo de solicitud
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                    'error_details' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }

            return back()->with('error', $errorMsg)->withInput();
        }
    }

    /**
     * Obtiene el historial de entradas y salidas de un insumo
     */
    public function historial(Request $request, string $id)
    {
        try {
            $insumo = Insumo::findOrFail($id);

            // Obtener entradas del inventario
            $entradas = InventarioInsumo::where('insumo_id', $insumo->id)
                ->orderBy('fecha_compra', 'desc')
                ->get()
                ->map(function ($entrada) {
                    // Calcular cantidad original (si no existe en BD, usar la actual como fallback)
                    $cantidadOriginal = $entrada->cantidad_original ?? $entrada->cantidad;
                    // Asegurar que el costo unitario es float
                    $costoUnitario = (float)($entrada->costo_unitario ?? 0);
                    $cantidadRestante = (float)($entrada->cantidad ?? 0); // Cantidad actual del lote

                    return [
                        'fecha' => $entrada->fecha_compra->format('d/m/Y'),
                        'fecha_vencimiento' => $entrada->fecha_caducidad ? $entrada->fecha_caducidad->format('d/m/Y') : '-', // Manejar null
                        'cantidad_original' => $cantidadOriginal,
                        'cantidad_restante' => $cantidadRestante, // Cantidad actual/restante del lote
                        'unidad_medida' => $entrada->insumo->unidad_medida,
                        'valor_unitario' => $costoUnitario,
                        'valor_total_original' => $cantidadOriginal * $costoUnitario, // Valor de la compra original
                        'proveedor' => $entrada->proveedor,
                        'observaciones' => $entrada->observaciones
                    ];
                });

            // Obtener salidas (movimientos de tipo salida)
            $salidas = MovimientoInsumo::where('insumo_id', $insumo->id)
                ->where('tipo_movimiento', 'salida')
                // Quitar ->with('usuario') que causaba error
                // ->with('usuario') // Cargar relación usuario si no está ya cargada globalmente
                ->orderBy('fecha_movimiento', 'desc')
                ->get()
                ->map(function ($salida) use ($insumo) { // Pasar $insumo para obtener precio_referencia
                    // Forzar conversión a float para asegurar cálculo numérico
                    $valorUnitario = (float) ($salida->costo_unitario ?? $insumo->precio_referencia ?? 0); // Priorizar costo_unitario de salida si existe
                    $cantidad = (float) $salida->cantidad;
                    $valorTotal = $cantidad * $valorUnitario;
                    return [
                        'fecha' => $salida->fecha_movimiento->format('d/m/Y'),
                        'cantidad' => $salida->cantidad, // Mantener original para mostrar
                        'unidad_medida' => $insumo->unidad_medida, // Usar unidad del insumo principal
                        'valor_unitario' => $valorUnitario, // Usar el valor calculado
                        'valor_total' => $valorTotal,       // Usar el valor calculado
                        'destino' => $salida->motivo,
                        // Usar la relación existente (probablemente 'creadoPor')
                        'responsable' => $salida->creadoPor->name ?? 'N/A',
                        'observaciones' => $salida->observaciones ?? '' // Asegurar que no sea null
                    ];
                });

            return response()->json([
                'success' => true,
                'entradas' => $entradas,
                'salidas' => $salidas
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al obtener historial de insumo', [
                'error' => $e->getMessage(),
                'insumo_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el historial del insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesa el registro de salida de insumos.
     *
     * Nota: Esta función fue movida/reemplazada por la lógica en AplicacionInsumosController@salidaStore
     * ya que la ruta web.php apunta a ese controlador para la acción 'insumos.salida.store'.
     * Se deja comentada/eliminada para evitar confusión.
     */
    /*
    public function salidaStore(Request $request)
    {
        // ... (código eliminado)
    }
    */
}
