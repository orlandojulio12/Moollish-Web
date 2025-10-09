<?php

namespace App\Http\Controllers;

use App\Models\Medicacion;
use App\Models\Veterinario;
use App\Models\Palpacion;
use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Predios;
use App\Models\AnimalEstadoReproductivo;
use App\Models\AnimalEstadoProductivo;
use App\Models\EstadoReproductivo;
use App\Models\EstadoProductivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PartoAnimal;
use App\Models\PesajeAnimal;
use App\Models\Inventario;
use App\Models\RegistroCompra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Pajilla;
use App\Models\AjustePajilla;
use App\Models\InseminacionArtificial;
use App\Models\TransferenciaEmbrion;
use App\Models\embrion;
use Illuminate\Support\Facades\Storage;
use App\Models\Insumo;
use App\Models\CategoriaInsumo;
use App\Models\MovimientoInsumo;
use App\Models\InventarioInsumo;
use App\Models\Movimientos;
use App\Imports\AnimalesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnimalesTemplateExport;

class AnimalesController extends Controller
{

    public function medicacion()
    {
        $user = Auth::user();

        if ($user->role_id == 1) {
            $predios = Predios::all();
        } else {
            $predios = $user->predios;
        }
        $predioIds = $predios->pluck('id')->toArray();

        $animales = Animal::whereIn('id_predio', $predioIds)->get();
        $medicaciones = Medicacion::with(['animal', 'veterinario', 'predio', 'insumo'])
            ->whereIn('id_predio', $predioIds)
            ->orderBy('fecha_medicacion', 'desc')->get();
        /* return $medicaciones; */
        $veterinarios = Veterinario::all();

        $categoriasRelevantesIds = CategoriaInsumo::whereIn('nombre', ['sanidad', 'vacunas'])
            ->pluck('id')
            ->toArray();

        // Obtener insumos base filtrados por predio y categoría
        $insumosBase = Insumo::whereIn('predio_id', $predioIds)
            ->whereIn('categoria_id', $categoriasRelevantesIds)
            // ->where('activo', true) // Ya no filtramos por activo aquí, sino por stock
            ->orderBy('nombre_comercial')
            ->get(); // Obtener la colección completa primero

        // Filtrar insumos por stock > 0
        $insumosConStock = $insumosBase->filter(function ($insumo) {
            return $insumo->stockActual() > 0;
        })->map(function ($insumo) {
            // Devolvemos solo los datos necesarios para el select y JS
            $stock = $insumo->stockActual(); // Calcular stock una vez
            return [
                'id' => $insumo->id,
                'nombre_comercial' => $insumo->nombre_comercial,
                'unidad_medida' => $insumo->unidad_medida,
                'predio_id' => $insumo->predio_id, // Necesario para filtrar en JS
                'stock' => $stock // <-- Añadir stock
            ];
        });

        return view('inventario_animales.medicacion', compact(
            'animales',
            'predios',
            'medicaciones',
            'veterinarios',
            'insumosConStock' // Pasar la lista filtrada por stock
        ));
    }
  public function import(Request $request)
{
    $request->validate([
        'predio_id' => 'required|exists:predios,id',
        'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
    ]);

    try {
        $file = $request->file('file');
        $predio_id = $request->predio_id;

        $import = new AnimalesImport($predio_id);
        
        Excel::import($import, $file);

        // Registrar partos automáticos
        $import->registrarPartosAutomaticos();

        $errores = $import->getErrores();
        
        if (!empty($errores)) {
            return redirect()->back()
                ->with('warning', 'Importación completada con advertencias:')
                ->with('errores', $errores);
        }

        return redirect()->back()->with('success', 'Animales importados exitosamente');

    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
        $errores = [];
        
        foreach ($failures as $failure) {
            $errores[] = "Fila {$failure->row()}: " . implode(', ', $failure->errors());
        }
        
        return redirect()->back()
            ->with('error', 'Error de validación')
            ->with('errores', $errores);
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al importar: ' . $e->getMessage());
    }
}

/**
 * Descargar plantilla Excel
 */
public function downloadTemplate()
{
    return Excel::download(new AnimalesTemplateExport(), 'plantilla_animales.xlsx');
}


    public function medicacionPost(Request $request)
    {
        // Iniciar transacción
        DB::beginTransaction();

        try {
            // Validar los datos del formulario, incluyendo los nuevos campos
            $validatedData = $request->validate([
                'fecha_medicacion' => 'required|date',
                'motivo'           => 'required|string|max:255', // Permitir cualquier string (incluido 'Otro')
                'id_animal'        => 'required|exists:animales,id_animal',
                'observacion'      => 'nullable|string',
                'id_veterinario'   => 'required|exists:veterinarios,id',
                'id_predio'        => 'required|exists:predios,id',
                // Nuevos campos
                'insumo_id'        => 'nullable|exists:insumos,id',
                'cantidad' => 'nullable|required_with:insumo_id|numeric|min:0.01',
                'via_administracion' => 'nullable|string|max:50|required_with:insumo_id',
            ], [
                // Mensajes personalizados si se desean
                'cantidad.required_with' => 'La cantidad es requerida si selecciona un insumo.',
                'via_administracion.required_with' => 'La vía de administración es requerida si selecciona un insumo.',
            ]);

            //dd($validatedData);

            // Preparar datos para crear Medicacion
            $medicacionData = [
                'fecha_medicacion' => $validatedData['fecha_medicacion'],
                'motivo'           => $validatedData['motivo'],
                'id_animal'        => $validatedData['id_animal'],
                'observacion'      => $validatedData['observacion'],
                'id_veterinario'   => $validatedData['id_veterinario'],
                'id_predio'        => $validatedData['id_predio'], // Guardar predio
                // Añadir nuevos campos si están presentes
                'insumo_id'        => $validatedData['insumo_id'] ?? null,
                'cantidad' => $validatedData['cantidad'] ?? null,
                'via_administracion' => $validatedData['via_administracion'] ?? null,
            ];

            // Crear el registro de medicación
/*             try {
                $medicacion = Medicacion::create($medicacionData);
                Log::info('Registro de medicación creado', ['medicacion_id' => $medicacion->id]);
            } catch (\Exception $e) {
                dd('Error al crear medicación', $e->getMessage(), $e->getTraceAsString());
            } */

                 $medicacion = Medicacion::create($medicacionData);
                Log::info('Registro de medicación creado', ['medicacion_id' => $medicacion->id]);

            // Procesar salida de insumo si se seleccionó uno
            if (!empty($validatedData['insumo_id'])) {
                $insumo = Insumo::with('categoria')->findOrFail($validatedData['insumo_id']);
                $cantidad = (float) $validatedData['cantidad'];

                Log::info('Procesando salida de insumo', ['insumo_id' => $insumo->id, 'cantidad' => $cantidad]);

                // Verificar categoría (Ajusta nombres si es necesario)
                $categoriasRelevantes = ['sanidad', 'vacunas'];
                // Cambiar a verificación insensible a mayúsculas/minúsculas
                $categoriaCoincideInsensible = $insumo->categoria && in_array(
                    strtolower($insumo->categoria->nombre),
                    array_map('strtolower', $categoriasRelevantes)
                );
                // Usar la verificación insensible
                if ($categoriaCoincideInsensible) {

                    // Verificar stock
                    $stockActual = $insumo->stockActual();
                    if ($cantidad > $stockActual) {
                        // Si la validación falla aquí (debería ser raro), cancelar transacción
                        DB::rollBack();
                        Log::warning('Intento de aplicar más insumo que el disponible', [
                            'insumo_id' => $insumo->id,
                            'solicitado' => $cantidad,
                            'disponible' => $stockActual
                        ]);
                        return redirect()->back()
                            ->with('error', "Stock insuficiente para '{$insumo->nombre_comercial}'. Disponible: {$stockActual} {$insumo->unidad_medida}.")
                            ->withInput();
                    }

                    // Registrar movimiento de salida
                    // Obtener el animal para su código
                    $animal = Animal::find($validatedData['id_animal']);
                    $codigoAnimal = $animal ? $animal->codigo : 'ID ' . $validatedData['id_animal']; // Usar código o ID como fallback

                    // Formatear la fecha de medicación
                    $fechaFormateada = \Carbon\Carbon::parse($medicacion->fecha_medicacion)->translatedFormat('d \d\e F \d\e\l Y'); // Formato "14 de febrero de 2025"

                    // --- INICIO: Lógica de Salida Completa (adaptada de AplicacionInsumosController) ---

                    // 2. (MOVIDO ANTES) Actualizar el inventario (reducir stock y calcular costo real)
                    $inventarioItems = InventarioInsumo::where('insumo_id', $insumo->id)
                        ->where('cantidad', '>', 0) // Solo considerar lotes con stock
                        ->orderBy('fecha_caducidad', 'asc') // FIFO o FEFO
                        ->orderBy('fecha_compra', 'asc')   // FIFO si no hay caducidad
                        ->get();

                    $cantidadRestantePorDescontar = $cantidad;
                    $costoTotalSalidaCalculado = 0;

                    // Usaremos una copia temporal para no afectar la transacción si falla el cálculo
                    $idsInventarioActualizar = [];
                    $cantidadesInventarioActualizar = [];

                    foreach ($inventarioItems as $item) {
                        if ($cantidadRestantePorDescontar <= 0) break;

                        $cantidadADescontarEsteItem = min($cantidadRestantePorDescontar, $item->cantidad);
                        $costoItemDescontado = $cantidadADescontarEsteItem * $item->costo_unitario;

                        // Guardar temporalmente qué actualizar
                        $idsInventarioActualizar[] = $item->id;
                        $cantidadesInventarioActualizar[$item->id] = $item->cantidad - $cantidadADescontarEsteItem;

                        // Acumular costo y reducir cantidad restante
                        $costoTotalSalidaCalculado += $costoItemDescontado;
                        $cantidadRestantePorDescontar -= $cantidadADescontarEsteItem;

                        Log::info('Cálculo para descontar de lote de inventario', [
                            'inventario_id' => $item->id,
                            'cantidad_a_descontar' => $cantidadADescontarEsteItem,
                            'costo_unitario_lote' => $item->costo_unitario,
                            'costo_parcial_salida' => $costoItemDescontado,
                            'cantidad_original_lote' => $item->cantidad
                        ]);
                    }
                    // Verificar si se pudo cubrir toda la cantidad
                    if ($cantidadRestantePorDescontar > 0.001) { // Margen pequeño para errores de float
                        // ¡Error crítico! No hay suficiente stock registrado en lotes para cubrir la salida
                        // Esto no debería pasar si la verificación inicial de stockActual() funcionó.
                        DB::rollBack(); // Revertir transacción
                        Log::critical('Error crítico: Stock insuficiente detectado durante el cálculo de costo de salida. La verificación inicial pudo ser incorrecta.', [
                            'insumo_id' => $insumo->id,
                            'cantidad_solicitada' => $cantidad,
                            'cantidad_faltante' => $cantidadRestantePorDescontar,
                            'stock_reportado_inicialmente' => $stockActual // El valor de la primera verificación
                        ]);
                        return redirect()->back()
                            ->with('error', "Error crítico de inventario para '{$insumo->nombre_comercial}'. Contacte soporte.")
                            ->withInput();
                    }

                    Log::info('Costo total de salida calculado', ['costo_total' => $costoTotalSalidaCalculado]);

                    // Calcular costo unitario promedio REAL para esta salida
                    $costoUnitarioRealSalida = ($cantidad > 0) ? $costoTotalSalidaCalculado / $cantidad : 0;
                    Log::info('Costo unitario promedio real calculado para esta salida', ['costo_unitario_real' => $costoUnitarioRealSalida]);

                    // AHORA SÍ: Actualizar el inventario REALMENTE
                    foreach ($idsInventarioActualizar as $idInventario) {
                        InventarioInsumo::where('id', $idInventario)->update(['cantidad' => $cantidadesInventarioActualizar[$idInventario]]);
                        Log::info('Lote de inventario actualizado', ['inventario_id' => $idInventario, 'nueva_cantidad' => $cantidadesInventarioActualizar[$idInventario]]);
                    }

                    // 1. Registrar movimiento de salida (usando el costo unitario REAL calculado)
                    $movimiento = MovimientoInsumo::create([
                        'insumo_id' => $insumo->id,
                        'tipo_movimiento' => 'salida',
                        'cantidad' => $cantidad,
                        // Usar el costo unitario REAL promedio calculado para este movimiento
                        'costo_unitario' => $costoUnitarioRealSalida,
                        'fecha_movimiento' => $validatedData['fecha_medicacion'],
                        'motivo' => "Aplicado en medicación del día {$fechaFormateada} al animal {$codigoAnimal}",
                        'predio_id' => $validatedData['id_predio'],
                        'created_by' => Auth::id(),
                    ]);
                    Log::info('Movimiento de salida de insumo registrado por medicación (con costo real)', [
                        'movimiento_id' => $movimiento->id,
                        'insumo_id' => $insumo->id,
                        'cantidad' => $cantidad,
                        'costo_unitario_aplicado' => $costoUnitarioRealSalida // Loguear el costo aplicado
                    ]);

                    // 2. (Eliminado porque se movió antes)

                    // 3. Registrar movimiento económico (usando el costo TOTAL calculado)
                    if ($costoTotalSalidaCalculado > 0) {
                        // 3.1. Movimiento de Costo/Gasto (Débito)
                        $subcuentaCostoId = $insumo->plan_cuenta; // Usar la cuenta definida en el insumo

                        if ($subcuentaCostoId) {
                            $movimientoCosto = Movimientos::create([
                                'usuario_id' => Auth::id(),
                                'id_predio' => $validatedData['id_predio'],
                                'cantidad' => $costoTotalSalidaCalculado, // Débito
                                'fecha' => $validatedData['fecha_medicacion'],
                                'descripcion' => "Costo por medicación: {$insumo->nombre_comercial} a animal {$codigoAnimal}",
                                'plan_cuenta' => $subcuentaCostoId,
                            ]);
                            Log::info('Movimiento Débito (Costo) registrado por medicación', [
                                'movimiento_id' => $movimientoCosto->id,
                                'cuenta_costo_id' => $subcuentaCostoId,
                                'monto' => $costoTotalSalidaCalculado
                            ]);
                        } else {
                            Log::warning('El insumo no tiene definida una cuenta contable (plan_cuenta) para registrar el costo de salida por medicación.', [
                                'insumo_id' => $insumo->id,
                                'insumo_nombre' => $insumo->nombre_comercial
                            ]);
                        }

                        // 3.2. Movimiento de Inventario (Crédito)
                        // !! IMPORTANTE: Usar el ID correcto de la cuenta de Activo para 'Inventario de Insumos' !!
                        $idCuentaActivoInventario = 88; // Reemplazar si es necesario o buscar dinámicamente

                        if ($idCuentaActivoInventario) {
                            $movimientoInventario = Movimientos::create([
                                'usuario_id' => Auth::id(),
                                'id_predio' => $validatedData['id_predio'],
                                'cantidad' => $costoTotalSalidaCalculado, // Crédito (la cantidad es positiva, la naturaleza de la cuenta lo hace crédito)
                                'fecha' => $validatedData['fecha_medicacion'],
                                'descripcion' => "Crédito inventario por medicación: {$insumo->nombre_comercial} a animal {$codigoAnimal}",
                                'plan_cuenta' => $idCuentaActivoInventario,
                            ]);
                            Log::info('Movimiento Crédito (Inventario) registrado por medicación', [
                                'movimiento_id' => $movimientoInventario->id,
                                'cuenta_activo_id' => $idCuentaActivoInventario,
                                'monto' => $costoTotalSalidaCalculado
                            ]);
                        } else {
                            Log::error("No se encontró la cuenta de Activo 'Inventario de Insumos' (ID: {$idCuentaActivoInventario}) para registrar el crédito por medicación.");
                            // Considerar si lanzar una excepción
                        }
                    } else {
                        Log::info('No se registró movimiento económico porque el costo total calculado fue cero.', [
                            'insumo_id' => $insumo->id
                        ]);
                    }
                    // --- FIN: Lógica de Salida Completa ---

                } else {
                    // Actualizar log para reflejar la verificación
                    Log::info('Insumo seleccionado no pertenece a categoría Sanidad/Vacunas (insensible), no se registra salida.', [
                        'insumo_id' => $insumo->id,
                        'categoria' => $insumo->categoria->nombre ?? 'N/A'
                    ]);
                }
            }

            // Dump and Die ANTES de confirmar la transacción


            // Confirmar transacción
            DB::commit();

            return redirect()->route('medicacion.index')
                ->with('success', 'Registro de medicación guardado exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack(); // Revertir en caso de error de validación
            Log::warning('Error de validación en medicacionPost', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir en caso de error general
            Log::error('Error en medicacionPost: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Ocurrió un error al registrar la medicación.')->withInput();
        }
    }

    public function embriones()
    {
        $user = Auth::user();
        if ($user->role_id == 1) {
            $predios = Predios::all();
        } else {
            $predios = $user->predios;
        }
        $predioIds = $predios->pluck('id')->toArray();
        $embriones = embrion::whereIn('id_predio', $predioIds)
            ->orderBy('fecha_entrada', 'desc')
            ->get();
        return view('inventario_animales.embriones', compact('predios', 'embriones'));
    }

    public function embrionesPost(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'id_predio'           => 'required|exists:predios,id',
            'codigo_embrion'      => 'required|string',
            'nombre_reproductor'  => 'required|string',
            'raza_reproductor'    => 'required|string',
            'vaca_donadora'       => 'required|string',
            'raza_vaca'           => 'required|string',
            'vendedor'            => 'required|string',
            'costo_unidad'        => 'required|numeric',
            'fecha_entrada'       => 'required|date',
            'cantidad'            => 'required|integer|min:1',
        ]);

        // Calcular el valor total (cantidad * costo por unidad)
        $costo = $request->input('costo_unidad');
        $cantidad = $request->input('cantidad');
        $valor_total = $costo * $cantidad;

        // Crear el registro de embrión
        embrion::create([
            'id_predio'           => $request->input('id_predio'),
            'codigo_embrion'      => $request->input('codigo_embrion'),
            'nombre_reproductor'  => $request->input('nombre_reproductor'),
            'raza_reproductor'    => $request->input('raza_reproductor'),
            'vaca_donadora'       => $request->input('vaca_donadora'),
            'raza_vaca'           => $request->input('raza_vaca'),
            'vendedor'            => $request->input('vendedor'),
            'costo_unidad'        => $costo,
            'fecha_entrada'       => $request->input('fecha_entrada'),
            'cantidad'            => $cantidad,
            'valor_total'         => $valor_total,
        ]);

        return redirect()->route('embriones.index')
            ->with('success', 'Embriones registrados exitosamente.');
    }

    public function transferenciaHembriones()
    {
        $user = Auth::user();

        if ($user->role_id == 1) {
            $predios = Predios::all();
        } else {
            $predios = $user->predios; // Asumiendo la relación $user->predios
        }

        $embriones = embrion::whereIn('id_predio', $predios->pluck('id'))->get();

        $transferenciasQuery = TransferenciaEmbrion::with(['receptora', 'predio']);

        if ($user->role_id != 1) {
            $transferenciasQuery->whereIn('predio_id', $predios->pluck('id'));
        }

        $transferencias = $transferenciasQuery->orderBy('fecha_transferencia', 'desc')->get();

        $animalesQuery = Animal::where('sexo', 'Hembra');

        if ($user->role_id != 1) {
            $animalesQuery->whereIn('id_predio', $predios->pluck('id'));
        }

        $animales = $animalesQuery->get();

        return view('inventario_animales.transferenciaEmbriones', [
            'predios' => $predios,
            'transferencias' => $transferencias,
            'animales' => $animales,
            'embriones' => $embriones
        ]);
    }

    public function buscarAnimalesPorPredio(Request $request)
    {
        $predioId = $request->input('predio_id');
        $animales = Animal::where('sexo', 'Hembra')
            ->where('id_predio', $predioId)
            ->whereHas('ultimoTacto', function ($query) {
                $query->where('resultado', 'Vacia')
                    ->whereNotNull('diagnostico')
                    ->where('diagnostico', '!=', '');
            })
            ->whereHas('medicaciones', function ($query) {
                $query->where('motivo', 'Transferencia de Embriones');
            })
            ->get();

        return response()->json($animales);
    }

    public function buscarEmbrionesPorPredio(Request $request)
    {
        $predioId = $request->input('predio_id');

        // Se obtienen los embriones que pertenecen al predio indicado
        $embriones = embrion::where('id_predio', $predioId)->get();

        return response()->json($embriones);
    }

    public function transferenciaHembrionesStore(Request $request)
    {
        // 1. Validar los datos que realmente se envían desde la vista
        $request->validate([
            'predio_id'           => 'required|integer|exists:predios,id',
            'fecha_transferencia' => 'required|date',
            'embrion_id'          => 'required|integer|exists:embriones,id',
            'receptora_id'        => 'required|integer|exists:animales,id_animal',
            'observaciones'       => 'nullable|string',
        ]);

        // 2. Chequear permisos según el rol del usuario
        $user = Auth::user();
        if ($user->role_id != 1) {
            $prediosUsuario = $user->predios->pluck('id')->toArray();
            if (!in_array($request->predio_id, $prediosUsuario)) {
                return redirect()->back()
                    ->with('error', 'No tienes permiso para registrar transferencias en este predio.')
                    ->withInput();
            }
        }

        // 3. Verificar que la vaca receptora cumpla las condiciones:
        //    a) Al menos una palpación con resultado "Vacia" y con diagnóstico no vacío.
        //    b) Al menos un registro en medicacion_animal con motivo "Transferencia de Embriones".
        $receptora = Animal::findOrFail($request->receptora_id);

        $palpacionValida = Palpacion::where('id_animal', $receptora->id_animal)
            ->where('resultado', 'Vacia')
            ->whereNotNull('diagnostico')
            ->where('diagnostico', '!=', '')
            ->exists();

        $medicacionValida = Medicacion::where('id_animal', $receptora->id_animal)
            ->where('motivo', 'Transferencia de Embriones')
            ->exists();

        if (!$palpacionValida) {
            return redirect()->back()
                ->with('error', 'La vaca receptora no tiene una palpación válida (resultado "Vacia" con diagnóstico).')
                ->withInput();
        }

        if (!$medicacionValida) {
            return redirect()->back()
                ->with('error', 'La vaca receptora no tiene registros de medicación.')
                ->withInput();
        }

        // 4. Intentar crear la transferencia
        try {
            TransferenciaEmbrion::create([
                'predio_id'           => $request->predio_id,
                'fecha_transferencia' => $request->fecha_transferencia,
                'id_embrion'          => $request->embrion_id,
                'id_vaca'             => $request->receptora_id, // asignamos la receptora al campo id_vaca
                'observaciones'       => $request->observaciones,
            ]);

            return redirect()->route('transferencia.hembriones')
                ->with('success', 'Transferencia registrada exitosamente!');
        } catch (\Exception $e) {
            Log::error('Error al registrar la transferencia: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al guardar la transferencia: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function proyeccionDestete()
    {
        $user = Auth::user();
        $predios = $user->predios;
        $estadosProductivos = [
            EstadoProductivo::VACA_PARIDA
        ];
        $animales = Animal::filtrarPorEstadoYPredio($user, $estadosProductivos)->get();
        return view('reportes.proyeccion_destetes', compact('animales'));
    }

    public function pajillas()
    {
        $user = Auth::user();
        $predios = $user->predios;
        if ($user->id_rol === 1) {
            $pajillas = Pajilla::all();
        } else {
            $pajillas = Pajilla::whereIn('id_predio', $user->predios->pluck('id'))->get();
        }
        return view('inventario_animales.Pajillas', compact(
            'pajillas',
            'predios'
        ));
    }

    public function pajillasAjuste(Request $request)
    {
        $request->validate([
            'id_pajilla' => 'required|exists:pajillas_semen,id',
            'cantidad' => 'required|integer|min:0',
            'motivo' => 'required|string',
            'observacion' => 'nullable|string',
        ]);

        $pajilla = Pajilla::findOrFail($request->id_pajilla);

        // Guardar el ajuste en la tabla ajustes_pajillas
        AjustePajilla::create([
            'fecha' => now(),
            'id_pajilla' => $pajilla->id,
            'cantidad' => $request->cantidad,
            'motivo' => $request->motivo,
            'observacion' => $request->observacion,
        ]);

        // Actualizar la cantidad en la tabla pajillas_semen
        $pajilla->cantidad = $request->cantidad;
        $pajilla->save();

        return redirect()->back()->with('success', 'Ajuste de pajilla guardado correctamente.');
    }

    public function pajillasStore(Request $request)
    {
        // Validación de los datos enviados desde el formulario
        $request->validate([
            'codigo'            => 'required|string|max:255|unique:pajillas_semen,codigo_pajilla',
            'nombre_reproductor' => 'nullable|string|max:255',
            'raza'              => 'required|string|max:255',
            'vendedor'          => 'nullable|string|max:255',
            'costo_unidad'      => 'required|numeric|min:0',
            'cantidad'          => 'required|integer|min:1',
            'id_predio'         => 'required|integer|min:1',
            'fecha_entrada'     => 'required|date',
        ]);
        try {
            // Crear el registro de pajilla
            Pajilla::create([
                'id_predio' => $request->input('id_predio'), // Asegúrate de que el usuario tenga esta relación
                'codigo_pajilla' => $request->input('codigo'),
                'nombre_reproductor' => $request->input('nombre_reproductor'),
                'raza' => $request->input('raza'),
                'vendedor' => $request->input('vendedor'),
                'costo_unidad' => $request->input('costo_unidad'),
                'fecha_entrada' => $request->input('fecha_entrada'),
                'cantidad' => $request->input('cantidad'),
                'valor_total' => $request->input('costo_unidad') * $request->input('cantidad'),
            ]);

            // Redirigir con un mensaje de éxito
            return redirect()->back()->with('success', 'Pajilla registrada exitosamente.');
        } catch (\Exception $e) {
            // Manejo de errores
            return redirect()->back()->with('error', 'Error al registrar la pajilla: ' . $e->getMessage());
        }
    }


    public function inseminacion()
    {
        $user = Auth::user();
        $estadosProductivos = [
            estadoProductivo::VACA_SECA,
            estadoProductivo::VACA_PARIDA,
            estadoProductivo::NOVILLA_VIENTRE,
        ];

        // Vacas (animales) disponibles para inseminación
        $vacas = Animal::filtrarPorEstadoYPredio($user, $estadosProductivos)->get();
        $predios = $user->predios;
        // Pajillas según el rol del usuario
        if ($user->id_rol === 1) {
            $pajillas = Pajilla::all();
        } else {
            $pajillas = Pajilla::whereIn('id_predio', $user->predios->pluck('id'))->get();
        }

        return view('inventario_animales.InseminacionArtificial', compact('vacas', 'predios', 'pajillas'));
    }

    public function registrarInseminacion(Request $request)
    {
        $request->validate([
            'id_vaca' => 'required|exists:animales,id_animal',
            'id_pajilla' => 'required|exists:pajillas_semen,id',
            'fecha_inseminacion' => 'required|date',
        ]);

        try {
            $pajilla = Pajilla::findOrFail($request->id_pajilla);

            if ($pajilla->cantidad <= 0) {
                return response()->json(['error' => 'La pajilla seleccionada no tiene suficiente cantidad disponible.'], 400);
            }

            DB::transaction(function () use ($request, $pajilla) {
                InseminacionArtificial::create([
                    'fecha_inseminacion' => $request->fecha_inseminacion,
                    'id_vaca' => $request->id_vaca,
                    'id_pajilla' => $request->id_pajilla,
                ]);
            });


            return response()->json(['success' => 'Inseminación registrada exitosamente.']);
        } catch (\Exception $e) {
            Log::error('Error al registrar inseminación:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            Log::info('Registrar inseminación ejecutado', $request->all());

            return response()->json(['error' => 'Ocurrió un error inesperado al registrar la inseminación.'], 500);
        }
    }


    public function getAnimales()
    {
        $animales = Animal::with('ubicaciones')->get();
        return view('inventario.index', compact('animales'));
    }

    public function getAnimalDetails($codigo)
    {

        $animal = Animal::where('id_animal', $codigo)
            ->with([
                'ultimoParto',
                'lote',
                'potrero',
                'predio',
                'movimientos.lote',
                'movimientos.potrero',
                'movimientos.predio',
                'pesajes',
                'estadoProductivoActual',
                'estadoReproductivoActual'
            ])
            ->first();

        if ($animal) {
            $ultimoParto = $animal->ultimoParto ? $animal->ultimoParto->fecha_parto : null;

            return response()->json([
                'success' => true,
                'animal' => $animal,
                'pesajes' => $animal->pesajes,
                'estadoProductivo' => $animal->estadoProductivoActual,
                'estadoReproductivo' => $animal->estadoReproductivoActual,
                'movimientos' => $animal->movimientos,
                'ultimo_parto' => $ultimoParto,
                'ultimoTacto' => $animal->ultimoTacto, // Agrega la información del último tacto

            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Animal no encontrado'
        ]);
    }


    public function getAnimalDetailsParto($id)
    {
        $animal = Animal::with(
            'pesajes',
            'estadoProductivoActual',
            'estadoReproductivoActual',
            'movimientos.lote',
            'movimientos.potrero',
            'movimientos.predio',
            'ultimoParto'
        )->where('id_animal', $id)->first();

        if ($animal) {
            return response()->json([
                'success' => true,
                'animal' => $animal,
                'pesajes' => $animal->pesajes,
                'estadoProductivo' => $animal->estadoProductivoActual,
                'estadoReproductivo' => $animal->estadoReproductivoActual,
                'movimientos' => $animal->movimientos,
                'ultimoParto' => $animal->ultimoParto,
                'ultimoTacto' => $animal->ultimoTacto, // Agrega la información del último tacto
                'IEP' => $animal->calcularIEP(), // Añade el IEP a la respuesta
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Animal no encontrado'
        ]);
    }


    public function create()
    {
        return view('animales.create');
    }

    public function getProductivoStates($animalId, $estadoProductivoId)
    {
        // Buscar el animal por su ID
        $animal = Animal::find($animalId);
        if (!$animal) {
            return response()->json(['error' => 'Animal no encontrado'], 404);
        }

        $estadoProductivo = EstadoProductivo::find($estadoProductivoId);
        if (!$estadoProductivo) {
            return response()->json(['error' => 'Estado productivo no encontrado'], 404);
        }

        // Depuración del sexo del animal y el estado productivo
        Log::debug('Sexo del animal: ' . $animal->sexo);
        Log::debug('Estado productivo: ' . $estadoProductivo->id . ' - ' . $estadoProductivo->nombre);

        // Lógica para filtrar los estados reproductivos según el estado  productivo
        switch ($estadoProductivo->id) {
            case 1: // Vaca parida
                $estadosProductivos = EstadoReproductivo::where('sexo', $animal->sexo)
                    ->where('nombre', ['Preñada', 'Vacia'])
                    ->get();
                break;

            case 2: //Vaca seca
                $estadosProductivos = EstadoReproductivo::where('sexo', $animal->sexo)
                    ->whereIn('nombre', ['Preñada', 'Vacia'])
                    ->get();
                break;

            case 3: //Novilla de vientre
                $estadosProductivos = EstadoReproductivo::where('sexo', $animal->sexo)
                    ->whereIn('nombre', ['Preñada', 'Vacia'])
                    ->get();
                break;

            case 4: //  Hembra de levante
                $estadosProductivos = EstadoReproductivo::where('sexo', $animal->sexo)
                    ->where('nombre', 'Desconocido')
                    ->get();
                break;

            case 7: // Cria Hembra
                $estadosProductivos = EstadoReproductivo::where('sexo', $animal->sexo)
                    ->where('nombre', 'Desconocido')
                    ->get();
                break;

            default:
                return response()->json(['error' => 'Estado reproductivo no válido'], 400);
        }

        // Devolver los estados productivos asociados con el estado reproductivo
        return response()->json($estadosProductivos);
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Log detallado para diagnóstico (incluye headers para sync)
            Log::info('Iniciando registro de animal', [
                'request_data' => $request->all(),
                'headers' => $request->headers->all(),
                'is_sync_request' => $request->has('is_sync')
            ]);

            // Convertir el valor del checkbox a booleano
            $request->merge([
                'isComprado' => $request->has('isComprado') ? true : false,
            ]);

            if (!Auth::user()->canAddAnimal()) {
                DB::rollBack();
                Log::warning('Intento de exceder límite de animales', ['user_id' => Auth::id()]);
                return response()->json([
                    'success' => false,
                    'error' => 'Has alcanzado el número máximo de animales permitidos por tu membresía.'
                ], 422);
            }

            // Validar datos del animal
            $validatedData = $request->validate([
                'id_predio' => 'required|integer|exists:predios,id',
                'codigo' => [
                    'required',

                    Rule::unique('animales', 'codigo')->where(function ($query) use ($request) {
                        return $query->where('id_predio', $request->id_predio);
                    }),
                ],
                'nombre' => 'nullable|string|max:255',
                'identificacion_electronica' => 'nullable|string|max:255',
                'id_sinigan' => 'nullable|string|max:255',
                'fecha_nacimiento' => 'required|date',
                'sexo' => 'required|in:macho,hembra',
                'raza' => 'nullable|string|max:255',
                'color' => 'nullable|string|max:255',
                'hierro' => 'nullable|string',
                'fecha_ingreso_hato' => 'nullable|date',
                'estado_productivo' => 'nullable|in:vaca_parida,vaca_seca,novilla_vientre,hembra_levante,cria_hembra,reproductor_toro,macho_ceba,macho_levante,cria_macho',
                'fecha_parto' => 'required_if:estado_productivo,vaca_parida|nullable|date',
                'tipo_parto' => 'required_if:estado_productivo,vaca_parida|nullable|in:Parto,Gemelar,Trillizo',
                'crias.*.codigo_cria' => [
                    'nullable',
                    Rule::unique('animales', 'codigo')->where(function ($query) use ($request) {
                        return $query->where('id_predio', $request->id_predio);
                    }),
                ],
                'crias.*.nombre_cria' => 'nullable|string|max:255',
                'crias.*.sexo_cria' => 'required_if:estado_productivo,vaca_parida|in:macho,hembra',
                'crias.*.raza_cria' => 'nullable|string|max:255',
                'crias.*.id_sinigan_cria' => 'nullable|string|max:255',
                'crias.*.color_cria' => 'nullable|string|max:255',
                'crias.*.identificacion_electronica_cria' => 'nullable|numeric',
                'crias.*.peso_al_nacer' => 'nullable|numeric',
                'isComprado' => 'nullable|boolean',
                'proveedor' => 'required_if:isComprado,true|nullable|string|max:255',
                'fechaCompra' => 'required_if:isComprado,true|nullable|date',
                'precioCompra' => 'required_if:isComprado,true|nullable|numeric|min:0',
            ]);

            // Crear el animal
            $animal = Animal::create([
                'id_predio' => $validatedData['id_predio'],
                'codigo' => $validatedData['codigo'],
                'nombre' => $validatedData['nombre'] ?? null,
                'identificacion_electronica' => $validatedData['identificacion_electronica'] ?? null,
                'id_sinigan' => $validatedData['id_sinigan'] ?? null,
                'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
                'sexo' => $validatedData['sexo'],
                'raza' => $validatedData['raza'] ?? null,
                'color' => $validatedData['color'] ?? null,
                'hierro' => $validatedData['hierro'] ?? null,
                'es_comprado' => $validatedData['isComprado'],
                'fecha_ingreso_hato' => $validatedData['fecha_ingreso_hato'] ?? null,
            ]);

            // Manejo del registro de compra
            if ($validatedData['isComprado']) {
                RegistroCompra::create([
                    'id_animal' => $animal->id_animal,
                    'proveedor' => $validatedData['proveedor'],
                    'fecha_compra' => $validatedData['fechaCompra'],
                    'precio_compra' => $validatedData['precioCompra'],
                ]);
            }

            Log::info('Animal creado exitosamente', ['animal_id' => $animal->id_animal]);

            // Asignar estados productivos que no requieren lógica adicional
            if ($request->estado_productivo) {
                $estadoProductivo = strtoupper($request->estado_productivo);

                if (defined('App\Models\EstadoProductivo::' . $estadoProductivo)) {
                    $idEstadoProductivo = constant('App\Models\EstadoProductivo::' . $estadoProductivo);
                    $animal->estado_productivo_id = $idEstadoProductivo;
                    $animal->save();

                    AnimalEstadoProductivo::create([
                        'id_animal' => $animal->id_animal,
                        'id_estado_productivo' => $idEstadoProductivo,
                        'fecha_inicio' => now(),
                        'fecha_fin' => null,
                    ]);
                    Log::info('Estado productivo asignado', ['animal_id' => $animal->id_animal, 'estado_id' => $idEstadoProductivo]);
                } else {
                    Log::warning('Estado productivo no válido recibido', ['estado' => $estadoProductivo]);
                    // Considerar lanzar una excepción o manejar el error de otra forma si es crítico
                    // throw new \Exception('Estado productivo no válido: ' . $estadoProductivo);
                }
            }

            if ($request->estado_productivo === 'vaca_parida' && $animal->sexo === 'hembra') {
                $this->handleVacaParida($request, $animal);
            }

            DB::commit();
            Log::info('Transacción completada para animal', ['animal_id' => $animal->id_animal]);

            // Devolver siempre JSON, incluyendo el objeto animal completo
            return response()->json([
                'success' => true,
                'message' => 'Animal registrado/sincronizado exitosamente.', // Mensaje genérico
                'animal' => $animal // Devolver el objeto animal completo
            ], 201); // Código 201: Created

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación al registrar animal: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'error' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log detallado del error
            Log::error('Error general al registrar animal: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all() // Loguear los datos de la petición que causó el error
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Hubo un error interno al registrar el animal.',

            ], 500);
        }
    }

    private function handleVacaParida(Request $request, Animal $animal)
    {
        Log::info('Asignando estado productivo y reproductivo para vaca parida');
        $animal->estado_productivo_id = EstadoProductivo::VACA_PARIDA;
        $animal->estado_reproductivo_id = EstadoReproductivo::VACIA;
        $animal->save();

        AnimalEstadoProductivo::create([
            'id_animal' => $animal->id_animal,
            'id_estado_productivo' => EstadoProductivo::VACA_PARIDA,
            'fecha_inicio' => $request->fecha_parto,
            'fecha_fin' => null,
        ]);

        AnimalEstadoReproductivo::create([
            'id_animal' => $animal->id_animal,
            'id_estado_reproductivo' => EstadoReproductivo::VACIA,
            'fecha_inicio' => $request->fecha_parto,
            'fecha_fin' => null,
        ]);

        $this->registerPartoAndCrias($request, $animal);
    }

    private function registerPartoAndCrias(Request $request, Animal $animal)
    {
        $crias = [];
        $numCrias = $request->tipo_parto === 'Parto' ? 1 : ($request->tipo_parto === 'Gemelar' ? 2 : 3);

        for ($i = 1; $i <= $numCrias; $i++) {
            $criaData = $request->input("crias.$i");

            if ($criaData) {
                $cria = Animal::create([
                    'id_predio' => $request->id_predio,
                    'codigo' => $criaData['codigo_cria'] ?? null,
                    'nombre' => $criaData['nombre_cria'] ?? 'Cría de ' . $animal->nombre,
                    'fecha_nacimiento' => $request->fecha_parto,
                    'sexo' => $criaData['sexo_cria'],
                    'id_sinigan' => $criaData['id_sinigan_cria'],
                    'identificacion_electronica' => $criaData['identificacion_electronica_cria'],
                    'raza' => $criaData['raza_cria'] ?? $animal->raza,
                    'color' => $criaData['color_cria'] ?? null,
                    'hierro' => $animal->hierro,
                    'madre' => $animal->id_animal,
                    'raza_madre' => $animal->raza,
                    'madre_nombre' => $animal->nombre,
                ]);

                $estadoProductivoCria = $cria->sexo === 'hembra' ? EstadoProductivo::CRIA_HEMBRA : EstadoProductivo::CRIA_MACHO;
                $cria->estado_productivo_id = $estadoProductivoCria;
                $cria->save();

                AnimalEstadoProductivo::create([
                    'id_animal' => $cria->id_animal,
                    'id_estado_productivo' => $estadoProductivoCria,
                    'fecha_inicio' => $request->fecha_parto,
                    'fecha_fin' => null,
                ]);

                if ($cria->sexo === 'hembra') {
                    AnimalEstadoReproductivo::create([
                        'id_animal' => $cria->id_animal,
                        'id_estado_reproductivo' => EstadoReproductivo::DESCONOCIDO,
                        'fecha_inicio' => $request->fecha_parto,
                        'fecha_fin' => null,
                    ]);
                }

                if (!empty($criaData['peso_al_nacer'])) {
                    PesajeAnimal::create([
                        'id_animal' => $cria->id_animal,
                        'peso' => $criaData['peso_al_nacer'],
                        'fecha' => $request->fecha_parto,
                    ]);
                }

                $crias[] = $cria;
            }
        }

        $id_cria_principal = count($crias) > 0 ? $crias[0]->id_animal : null;

        $parto = PartoAnimal::create([
            'id_animal' => $animal->id_animal,
            'id_cria' => $id_cria_principal,
            'fecha_parto' => $request->fecha_parto,
            'tipo_parto' => $request->tipo_parto,
            'observaciones' => 'si',
        ]);

        foreach ($crias as $cria) {
            $parto->criasViaPivot()->attach($cria->id_animal);
        }
    }

    public function inventarioGeneral()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener los predios asociados al usuario junto con sus lotes y potreros
        $predios = $user->predios()->with(['lotes', 'potreros'])->get();

        // Obtener los animales vivos junto con las relaciones necesarias
        $animales = Animal::with([
            'estadoProductivo',
            'estadoReproductivo',
            'predio',
            'potrero',
            'lote',
            'ultimoParto' // Incluimos la relación del último parto
        ])
            ->where('estado_vida', 1) // Solo animales vivos
            ->whereIn('id_predio', $user->predios->pluck('id')->toArray())
            ->get()
            ->map(function ($animal) {
                // Calcular edad en años y meses
                $fechaNacimiento = \Carbon\Carbon::parse($animal->fecha_nacimiento);
                $edadAnios = $fechaNacimiento->diffInYears(now());
                $edadMeses = $fechaNacimiento->diffInMonths(now()) % 12;

                // Calcular días de preñez basados en la fecha del último parto
                $diasDePrenez = 'N/A';

                if ($animal->estado_reproductivo_id === 1 && $animal->ultimoTacto) {
                    $fechaPartoProyectado = \Carbon\Carbon::parse($animal->ultimoTacto->parto_proyectado);
                    $fechaPreñada = $fechaPartoProyectado->subDays(285);
                    $diasDePrenez = $fechaPreñada->diffInDays(now());
                }

                // Calcular días de parida
                $diasDeParida = 'N/A';
                if ($animal->ultimoParto) {
                    $diasDeParida = floor(\Carbon\Carbon::parse($animal->ultimoParto->fecha_parto)->diffInDays(now()));
                }

                return [
                    'id_animal' => $animal->id_animal,
                    'codigo' => $animal->codigo,
                    'nombre' => $animal->nombre,
                    'identificacion_electronica' => $animal->identificacion_electronica,
                    'edad' => $edadAnios > 0 ? "{$edadAnios} años y {$edadMeses} meses" : "{$edadMeses} meses",
                    'estado_productivo_id' => $animal->estado_productivo_id,
                    'estado_productivo' => $animal->estadoProductivo->nombre ?? 'N/A',
                    'dias_de_parida' => $diasDeParida,
                    'estado_reproductivo_id' => $animal->estado_reproductivo_id,
                    'estado_reproductivo' => $animal->estadoReproductivo->nombre ?? 'N/A',
                    'dias_de_prenez' => $diasDePrenez,
                    'color' => $animal->color ?? 'N/A',
                    'sexo' => $animal->sexo ?? 'N/A',
                    'raza' => $animal->raza ?? 'N/A',
                    'fecha_nacimiento' => $animal->fecha_nacimiento ?? 'N/A',
                    'hierro' => $animal->hierro ?? 'N/A',
                    'lote' => $animal->lote->nombre ?? 'Sin Lote',
                    'predio' => $animal->predio->nombre_predio ?? 'Sin Predio',
                    'potrero' => $animal->potrero->nombre ?? 'Sin Potrero',
                ];
            });
        $totalAnimales = $animales->count();
        // Realizar los conteos necesarios para el inventario
        $hembrasTotales = $animales->where('sexo', 'hembra')->count();
        $machosTotales = $animales->where('sexo', 'macho')->count();
        $vacasPrenadas = $animales->where('sexo', 'hembra')->where('estado_reproductivo_id', EstadoReproductivo::PRENADA)->count();
        $vacasVacias = $animales->where('sexo', 'hembra')->where('estado_reproductivo_id', EstadoReproductivo::VACIA)->count();
        $criasHembra = $animales->where('sexo', 'hembra')->where('estado_productivo_id', EstadoProductivo::CRIA_HEMBRA)->count();
        $toros = $animales->where('sexo', 'macho')->where('estado_productivo_id', EstadoProductivo::REPRODUCTOR_TORO)->count();
        $criasMacho = $animales->where('sexo', 'macho')->where('estado_productivo_id', EstadoProductivo::CRIA_MACHO)->count();

        // Retornar la vista con los datos
        return view('reportes.inventario_general', compact(
            'animales',
            'predios',
            'hembrasTotales',
            'machosTotales',
            'vacasPrenadas',
            'vacasVacias',
            'criasHembra',
            'toros',
            'criasMacho',
            'totalAnimales'
        ));
    }

    public function filtrarInventario(Request $request)
    {
        $user = Auth::user();

        $query = Animal::with([
            'estadoProductivo',
            'estadoReproductivo',
            'predio',
            'potrero',
            'lote',
            'ultimoParto'
        ])
            ->where('estado_vida', Animal::ESTADO_VIVO)
            ->whereIn('id_predio', $user->predios->pluck('id')->toArray());

        // Aplicar filtros si están presentes
        if ($request->filled('predio')) {
            $query->where('id_predio', $request->predio);
        }

        if ($request->filled('potrero')) {
            $query->where('potrero_id', $request->potrero);
        }

        if ($request->filled('lote')) {
            $query->where('lote_id', $request->lote);
        }

        // Obtener los datos procesados como en el inventario general
        $animales = $query->get()->map(function ($animal) {
            $fechaNacimiento = \Carbon\Carbon::parse($animal->fecha_nacimiento);
            $edadAnios = $fechaNacimiento->diffInYears(now());
            $edadMeses = $fechaNacimiento->diffInMonths(now()) % 12;

            $diasDePrenez = 'N/A';
            if ($animal->estado_reproductivo_id === 1 && $animal->ultimoTacto) {
                $fechaPartoProyectado = \Carbon\Carbon::parse($animal->ultimoTacto->parto_proyectado);
                $fechaPreñada = $fechaPartoProyectado->subDays(285); // Restar 285 días
                $diasDePrenez = $fechaPreñada->diffInDays(now());
            }


            $diasDeParida = 'N/A';
            if ($animal->ultimoParto) {
                $diasDeParida = floor(\Carbon\Carbon::parse($animal->ultimoParto->fecha_parto)->diffInDays(now()));
            }

            return [
                'id_animal' => $animal->id_animal,
                'codigo' => $animal->codigo,
                'nombre' => $animal->nombre,
                'identificacion_electronica' => $animal->identificacion_electronica,
                'edad' => $edadAnios > 0 ? "{$edadAnios} años y {$edadMeses} meses" : "{$edadMeses} meses",
                'estado_productivo_id' => $animal->estado_productivo_id,
                'estado_productivo' => $animal->estadoProductivo->nombre ?? 'N/A',
                'dias_de_parida' => $diasDeParida,
                'estado_reproductivo_id' => $animal->estado_reproductivo_id,
                'estado_reproductivo' => $animal->estadoReproductivo->nombre ?? 'N/A',
                'dias_de_prenez' => $diasDePrenez,
                'color' => $animal->color ?? 'N/A',
                'sexo' => $animal->sexo ?? 'N/A',
                'raza' => $animal->raza ?? 'N/A',
                'fecha_nacimiento' => $animal->fecha_nacimiento ?? 'N/A',
                'hierro' => $animal->hierro ?? 'N/A',
                'lote' => $animal->lote->nombre ?? 'Sin Lote',
                'predio' => $animal->predio->nombre_predio ?? 'Sin Predio',
                'potrero' => $animal->potrero->nombre ?? 'Sin Potrero',
            ];
        });

        return response()->json(['animales' => $animales]);
    }

    public function inventarioFisico()
    {
        $user = Auth::user();
        $predios = $user->predios;

        $prediosuser = $user->predios->pluck('id');

        $inventarios = Inventario::whereIn('id_predio', $prediosuser)
            ->with(['predio', 'creador'])
            ->get()
            ->map(function ($inventario) {
                // Convertir los IDs de los animales y animales faltantes desde las cadenas separadas por comas
                $animales = is_array($inventario->animales)
                    ? $inventario->animales
                    : explode(',', $inventario->animales);

                $animalesFaltantes = is_array($inventario->animales_faltantes)
                    ? $inventario->animales_faltantes
                    : explode(',', $inventario->animales_faltantes);

                // Construir respuesta del inventario
                return [
                    'id_inventario' => $inventario->id_inventario,
                    'nombre_inventario' => $inventario->nombre_inventario ?? 'Sin nombre',
                    'id_predio' => $inventario->predio->nombre_predio,
                    'estado' => $inventario->estado,
                    'animales' => collect($animales)->map(function ($id_animal) {
                        $animalDetails = Animal::select('id_animal', 'codigo', 'nombre', 'sexo', 'raza')
                            ->where('id_animal', $id_animal)
                            ->first();
                        return $animalDetails ? $animalDetails->toArray() : null;
                    })->filter()->values()->all(), // Filtrar valores nulos
                    'animales_faltantes' => collect($animalesFaltantes)->map(function ($id_animal) {
                        $animalDetails = Animal::select('id_animal', 'codigo', 'nombre', 'sexo', 'raza')
                            ->where('id_animal', $id_animal)
                            ->first();
                        return $animalDetails ? $animalDetails->toArray() : null;
                    })->filter()->values()->all(), // Filtrar valores nulos
                    'cantidad_animales' => count($animales),
                    'cantidad_faltantes' => count($animalesFaltantes),
                    'fecha_inicio' => $inventario->fecha_inicio,
                    'observaciones' => $inventario->observaciones,
                ];
            });

        // Filtrar animales vivos asociados al usuario
        $animales = Animal::filtrarPorEstadoYPredio($user)
            ->with([
                'estadoProductivo',
                'estadoReproductivo',
                'lote',
                'potrero',
                'ultimoParto' // Incluimos la relación del último parto
            ])
            ->get()
            ->map(function ($animal) {
                // Calcular edad en años y meses
                $fechaNacimiento = \Carbon\Carbon::parse($animal->fecha_nacimiento);
                $edadAnios = $fechaNacimiento->diffInYears(now());
                $edadMeses = $fechaNacimiento->diffInMonths(now()) % 12;

                 // Calcular días de preñez basados en la fecha del último parto
                $diasDePrenez = 'N/A';

                if (
                    $animal->ultimaFechaDeParto &&
                    $animal->estadoReproductivo &&
                    $animal->estadoReproductivo->nombre === "Prenada"
                ) {
                }

                // Calcular días de parida asegurándonos de no tener decimale
                $diasDeParida = 'N/A';
                if ($animal->ultimoParto) {
                    $diasDeParida = floor(\Carbon\Carbon::parse($animal->ultimoParto->fecha_parto)->diffInDays(now()));
                }

                return [
                    'id_animal' => $animal->id_animal,
                    'codigo' => $animal->codigo,
                    'identificacion_electronica' => $animal->identificacion_electronica,
                    'edad' => $edadAnios > 0 ? "{$edadAnios} años y {$edadMeses} meses" : "{$edadMeses} meses",
                    'estado_productivo' => $animal->estadoProductivo->nombre ?? 'N/A',
                    'dias_de_parida' => $diasDeParida,
                    'estado_reproductivo' => $animal->estadoReproductivo->nombre ?? 'N/A',
                    'dias_de_prenez' => $diasDePrenez,
                    'color' => $animal->color ?? 'N/A',
                    'fecha_nacimiento' => $animal->fecha_nacimiento ?? 'N/A',
                    'hierro' => $animal->hierro ?? 'N/A',
                    'lote' => $animal->lote->nombre ?? 'Sin Lote',
                    'potrero' => $animal->potrero->nombre ?? 'Sin Potrero',
                ];
            });

        return view('reportes.inventarioFisico', compact('animales', 'predios', 'inventarios'));
    }

    public function storeInventario(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'id_predio'            => 'required|exists:predios,id',
                'observaciones'        => 'nullable|string',
                'fecha_fin'            => 'nullable|date',
                'fecha_inicio'         => 'nullable|date',
                'nombre_inventario'    => 'nullable|string',
                'estado'               => 'nullable|string',
                'animales'             => 'nullable|array',
                'animales.*'           => 'nullable|exists:animales,id_animal',
                'animales_faltantes'   => 'nullable|array',
                'animales_faltantes.*' => 'nullable|exists:animales,id_animal',
            ]);

            // Usar json_encode() para guardar el array como JSON
            $animales = $request->has('animales') ? $request->animales : null;
            $animalesFaltantes = $request->has('animales_faltantes') ? $request->animales_faltantes : null;

            $inventario = Inventario::create([
                'id_predio'           => $request->id_predio,
                'fecha_fin'           => $request->fecha_fin,
                'fecha_inicio'        => $request->fecha_inicio,
                'estado'              => $request->estado,
                'nombre_inventario'   => $request->nombre_inventario,
                'observaciones'       => $request->observaciones,
                'creado_por'          => Auth::id(),
                'animales'            => $animales,
                'animales_faltantes'  => $animalesFaltantes,
            ]);


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventario guardado correctamente.',
                'inventario' => $inventario,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar inventario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el inventario.',
            ], 500);
        }
    }

    public function updateInventario(Request $request, $id)
    {
        try {
            $inventario = Inventario::findOrFail($id);

            // Obtener los arrays de animales y animales_faltantes (se espera que sean arrays de IDs)
            $animales = $request->input('animales', []);
            $animalesFaltantes = $request->input('animales_faltantes', []);

            $inventario->update([
                'animales' => $animales,
                'animales_faltantes' => $animalesFaltantes,
                'estado' => $request->input('estado'),
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Inventario actualizado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el inventario.'
            ], 500);
        }
    }

    public function editInventario($id)
    {
        try {
            $inventario = Inventario::findOrFail($id);

            return response()->json([
                'id_inventario' => $inventario->id_inventario,
                'nombre_inventario' => $inventario->nombre_inventario,
                'id_predio' => $inventario->id_predio,
                'fecha_inicio' => $inventario->fecha_inicio,
                'fecha_fin' => $inventario->fecha_fin,
                'estado' => $inventario->estado,
                'observaciones' => $inventario->observaciones,
                'animales' => $inventario->animales, // Ya es un arreglo gracias al casting
                'animales_faltantes' => $inventario->animales_faltantes, // Igual
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al cargar el inventario.'], 500);
        }
    }

    public function verificarCodigo(Request $request)
    {
        $codigo = trim($request->input('cod_predio'));
        $idPredio = trim($request->input('id_predio'));

        // Verificar si los valores recibidos son correctos
        Log::debug('Código recibido: ' . $codigo . ', ID Predio recibido: ' . $idPredio);

        // Usando la relación predio para contar los animales con el código e id_predio
        $animalCount = Animal::where('codigo', $codigo)
            ->where('id_predio', $idPredio)
            ->count(); // Contar cuántos animales coinciden

        // Si hay más de 0 animales, el código ya existe
        $exists = $animalCount > 0;

        Log::debug('Resultado de la consulta count: ' . ($exists ? 'true' : 'false'));

        return response()->json(['exists' => $exists]);
    }

    /**
     * Almacena un animal creado offline.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeOffline(Request $request)
    {
        // Log inicial para depuración
        Log::info('Recibida solicitud para storeOffline:', $request->all());

        // Reglas de validación (pueden ser más flexibles que 'store' si algunos datos faltan offline)
        $rules = [
            'id_predio' => ['required_without:predio_temp_id', 'nullable', 'exists:predios,id'], // ID real o null si viene temp_id
            'predio_temp_id' => ['required_without:id_predio', 'nullable', 'string'], // ID temporal si no hay ID real
            'codigo' => ['required', 'string', 'max:255', Rule::unique('animales')->where(function ($query) use ($request) {
                // Asegurar unicidad dentro del predio (si ya tenemos el id_predio)
                if ($request->filled('id_predio')) {
                    return $query->where('id_predio', $request->id_predio);
                }
                return $query; // Si no hay id_predio aún, solo valida unicidad global (menos ideal)
            })],
            'fecha_nacimiento' => ['required', 'date'],
            'sexo' => ['required', 'in:macho,hembra'],
            'nombre' => ['nullable', 'string', 'max:255'],
            'identificacion_electronica' => ['nullable', 'string', 'max:255'],
            'id_sinigan' => ['nullable', 'string', 'max:255'],
            'raza' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'hierro' => ['nullable', 'numeric'],
            'estado_productivo' => ['nullable', 'string'], // Validar contra lista si es posible
            'fecha_ingreso_hato' => ['nullable', 'date'],
            // Campos de compra (solo si isComprado es true)
            'isComprado' => ['nullable', 'boolean'],
            'proveedor' => ['required_if:isComprado,true', 'nullable', 'string'],
            'fechaCompra' => ['required_if:isComprado,true', 'nullable', 'date'],
            'precioCompra' => ['required_if:isComprado,true', 'nullable', 'numeric'],
            // Otros campos que puedas haber añadido
        ];

        try {
            $validatedData = $request->validate($rules);

            // Preparar datos para crear el animal
            $animalData = $validatedData;
            $animalData['created_by'] = Auth::id(); // Asignar usuario autenticado como creador

            // Eliminar campos temporales/banderas antes de crear
            unset($animalData['predio_temp_id']);
            unset($animalData['pendiente_vinculacion_predio']); // Si se envía esta bandera
            unset($animalData['isComprado']); // La bandera no es parte del modelo Animal

            // Crear el animal
            $animal = Animal::create($animalData);

            // Si fue una compra, registrarla (si tienes esa lógica separada)
            if ($request->boolean('isComprado')) {
                RegistroCompra::create([
                    'id_animal' => $animal->id_animal,
                    'fecha_compra' => $validatedData['fechaCompra'],
                    'precio' => $validatedData['precioCompra'],
                    'proveedor' => $validatedData['proveedor'],
                    // Añadir otros campos relevantes si existen en RegistroCompra
                ]);
                Log::info('Registro de compra creado para animal:' . $animal->id_animal);
            }

            // Actualizar el inventario si es necesario (asumiendo que tienes un modelo Inventario)
            if ($request->filled('id_predio')) {
                Inventario::firstOrCreate(
                    ['insumo_id' => $animal->id_animal, 'predio_id' => $request->id_predio, 'tipo' => 'animal'], // Claves para buscar/crear
                    ['stock' => 1, 'stock_minimo' => 0, 'ubicacion' => 'N/A'] // Valores por defecto si se crea
                );
                Log::info('Inventario actualizado/creado para animal:' . $animal->id_animal . ' en predio: ' . $request->id_predio);
            } else {
                Log::warning('No se pudo actualizar inventario para animal offline porque id_predio no estaba disponible.');
            }

            Log::info('Animal creado offline exitosamente con ID:' . $animal->id_animal);

            // Devolver respuesta JSON exitosa con los datos del animal creado
            return response()->json([
                'success' => true,
                'message' => 'Animal sincronizado exitosamente.',
                'animal' => $animal // Enviar el objeto animal completo o los campos necesarios
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación en storeOffline: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error general en storeOffline: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Error interno del servidor al sincronizar el animal.'], 500);
        }
    }
}
