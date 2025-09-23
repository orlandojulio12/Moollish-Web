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


public function reporteTrasladosListado(Request $request)
{
    $user = Auth::user();

    // Consulta base para obtener los animales vivos
    $animalesQuery = Animal::with(['predio', 'lote', 'potrero', 'estadoProductivo', 'estadoReproductivo'])
        ->where('estado_vida', Animal::ESTADO_VIVO);

    // Si el usuario no es administrador, filtrar por sus predios
    if ($user->role->name !== 'admin') {
        $prediosUsuario = $user->predios()->pluck('predios.id')->toArray();
        $animalesQuery->whereIn('id_predio', $prediosUsuario);
    }

    // Aplicar filtros si existen
    if ($request->filled('predio_id')) {
        $animalesQuery->where('id_predio', $request->predio_id);
    }

    if ($request->filled('lote_id')) {
        $animalesQuery->where('lote_id', $request->lote_id);
    }

    if ($request->filled('potrero_id')) {
        $animalesQuery->where('potrero_id', $request->potrero_id);
    }

    if ($request->filled('animal_id')) {
        $animalesQuery->where('id_animal', $request->animal_id);
    }

    // Obtener los animales filtrados
    $animales = $animalesQuery->get();

    // Obtener información adicional para los filtros
    $predios = $user->role->name === 'admin'
        ? Predios::all()
        : $user->predios()->get();

    $lotes = Lote::whereIn('predio_id', $predios->pluck('id'))
        ->get();

    $potreros = Potrero::whereIn('predio_id', $predios->pluck('id'))
        ->get();

    // Obtener animales para el filtro por animal (opcional)
    $animalesFiltro = $user->role->name === 'admin'
        ? Animal::where('estado_vida', Animal::ESTADO_VIVO)->get()
        : Animal::where('estado_vida', Animal::ESTADO_VIVO)
            ->whereIn('id_predio', $predios->pluck('id'))
            ->get();

    return view('reportes.traslados', compact(
        'predios',
        'lotes',
        'potreros',
        'animales',
        'animalesFiltro'
    ));
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
}
