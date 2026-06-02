<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Lote;
use App\Models\Potrero;
use App\Models\EstadoReproductivo;
use App\Models\EstadoProductivo;
use App\Models\AnimalEstadoProductivo;
use App\Models\AnimalEstadoReproductivo;
use App\Models\PartoAnimal;
use App\Models\PesajeAnimal;
use App\Models\RazasGanado;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Imports\PartosImport;
use App\Exports\PartosTemplateExport;
use Maatwebsite\Excel\Facades\Excel;


class PartosController extends Controller
{
    //metodo para crear un parto
    public function create()
    {
        $user = Auth::user();
        $predios = $user->predios;
        $animales = Animal::whereIn('id_predio', $predios->pluck('id'))
            ->where('sexo', 'hembra')
            ->where('estado_reproductivo_id', EstadoReproductivo::PRENADA) // Usando constante
            ->get();
        $toros = Animal::whereIn('id_predio', $predios->pluck('id'))
            ->where('sexo', 'macho')
            ->where('estado_productivo_id', EstadoProductivo::REPRODUCTOR_TORO) // Usando constante
            ->get();
        $lotes = Lote::whereIn('predio_id', $predios->pluck('id'))->get();
        $potreros = Potrero::whereIn('predio_id', $predios->pluck('id'))->get();
        $estadosProductivos = EstadoProductivo::all();
        $estadosReproductivos = EstadoReproductivo::all();
        $razasGanado = RazasGanado::all();
        return view('inventario_animales.parto', compact(
            'animales',
            'toros',
            'razasGanado',
            'predios',
            'lotes',
            'potreros',
            'estadosProductivos',
            'estadosReproductivos'
        ));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            // Validación de datos de entrada
            $request->validate([
                'id_animal' => 'required|exists:animales,id_animal',
                'fecha_parto' => 'required|date',
                'tipo_parto' => 'required|in:Parto,Gemelar,Trillizo,Muerte Fetal,Aborto',
                'observaciones' => 'nullable|string|max:255',
                'padre' => 'nullable',
                'padre_nombre' => 'nullable'
            ]);

            // Buscar el animal seleccionado (la madre)
            $madre = Animal::findOrFail($request->id_animal);

            // Verificar que el animal sea hembra
            if ($madre->sexo !== 'hembra') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'El animal seleccionado no es una hembra.'
                ]);
            }

            // Obtener el parámetro de días de gestación para el predio
            // Suponemos que el modelo ParametroDiasGestacion ya fue creado
            $paramGestacion = \App\Models\ParametroDiasGestacion::where('predio_id', $madre->id_predio)->first();
            // Si no se encuentra, usamos el valor por defecto (280 días)
            $diasGestacion = $paramGestacion ? $paramGestacion->dias_gestacion : 280;

            // Validación adicional: si existe un último parto, verificar que hayan transcurrido los días de gestación definidos
            $ultimoParto = $madre->partos()->latest('fecha_parto')->first();
            if ($ultimoParto) {
                $diasDesdeUltimoParto = Carbon::parse($ultimoParto->fecha_parto)
                    ->diffInDays(Carbon::parse($request->fecha_parto));
                if ($diasDesdeUltimoParto < $diasGestacion) {
                    Log::error("La vaca aún no puede parir. Último parto fue hace {$diasDesdeUltimoParto} días, se requieren {$diasGestacion} días.");
                    return response()->json([
                        'status' => 'error',
                        'message' => "La vaca no puede parir, ya que su último parto fue hace {$diasDesdeUltimoParto} días. Se requieren al menos {$diasGestacion} días de gestación."
                    ]);
                }
            }
            // Validación del último tacto, si aplica
            $ultimoTacto = $madre->ultimoTacto()->first();
            if ($ultimoTacto) {
                $fechaProyectado = Carbon::parse($ultimoTacto->parto_proyectado);
                $fechaParto = Carbon::parse($request->fecha_parto);
                if ($fechaParto->lt($fechaProyectado) && !in_array($request->tipo_parto, ['Muerte Fetal', 'Aborto'])) {
                    Log::error('La vaca aún no puede parir por fecha proyectada.');
                    return response()->json([
                        'status' => 'error',
                        'message' => "La vaca aún no puede parir por fecha proyectada. dias de gestacion: {$diasGestacion}, fecha proyectada: {$fechaProyectado}, fecha parto: {$fechaParto}"
                    ]);
                }
            }
            // Lógica para registrar el parto y actualizar estados (sin cambios)
            $madre->estado_productivo_id = EstadoProductivo::VACA_PARIDA;
            $madre->estado_reproductivo_id = EstadoReproductivo::VACIA;
            $madre->save();
            AnimalEstadoProductivo::create([
                'id_animal' => $madre->id_animal,
                'id_estado_productivo' => EstadoProductivo::VACA_PARIDA,
                'fecha_inicio' => $request->fecha_parto,
                'fecha_fin' => null,
            ]);
            AnimalEstadoReproductivo::create([
                'id_animal' => $madre->id_animal,
                'id_estado_reproductivo' => EstadoReproductivo::VACIA,
                'fecha_inicio' => $request->fecha_parto,
                'fecha_fin' => null,
            ]);
            // Lógica para procesar las crías y el registro del parto (igual que antes)
            $crias = [];
            if (in_array($request->tipo_parto, ['Parto', 'Gemelar', 'Trillizo'])) {
                $numCrias = $request->tipo_parto === 'Parto' ? 1 : ($request->tipo_parto === 'Gemelar' ? 2 : 3);
                for ($i = 1; $i <= $numCrias; $i++) {
                    $criaData = $request->input('crias')[$i] ?? null;
                    if ($criaData) {
                        $request->validate([
                            "crias.$i.codigo_cria" => 'nullable|unique:animales,codigo',
                            "crias.$i.sexo_cria" => 'required|in:macho,hembra',
                        ]);
                        $cria = Animal::create([
                            'id_predio' => $criaData['predio_id_cria'] ?? $madre->id_predio,
                            'codigo' => $criaData['codigo_cria'] ?? null,
                            'nombre' => $criaData['nombre_cria'] ?? 'Cría de ' . $madre->nombre,
                            'identificacion_electronica' => $criaData['identificacion_electronica_cria'] ?? null,
                            'id_sinigan' => $criaData['id_sinigan_cria'] ?? null,
                            'fecha_nacimiento' => $request->fecha_parto,
                            'sexo' => $criaData['sexo_cria'],
                            'raza' => $criaData['raza_cria'] ?? null,
                            'color' => $criaData['color_cria'] ?? null,
                            'hierro' => $criaData['hierro_cria'] ?? null,
                            'potrero_id' => $criaData['potrero_id_cria'] ?? null,
                            'lote_id' => $criaData['lote_id_cria'] ?? null,
                            'madre' => $madre->id_animal,
                            'madre_nombre' => $madre->nombre,
                            'raza_madre' => $madre->raza,
                            'padre' => $request->padre === 'otro' ? null : $request->padre,
                            'raza_padre' => $this->obtenerRazaPadre($request->padre),
                            'padre_nombre' => $this->obtenerNombrePadre($request->padre),
                        ]);
                        $estadoProductivoCriaId = $cria->sexo === 'hembra' ? EstadoProductivo::CRIA_HEMBRA : EstadoProductivo::CRIA_MACHO;
                        $cria->estado_productivo_id = $estadoProductivoCriaId;
                        $cria->save();
                        AnimalEstadoProductivo::create([
                            'id_animal' => $cria->id_animal,
                            'id_estado_productivo' => $estadoProductivoCriaId,
                            'fecha_inicio' => $request->fecha_parto,
                            'fecha_fin' => null,
                        ]);

                        if ($cria->sexo === 'hembra') {
                            $cria->estado_reproductivo_id = EstadoReproductivo::DESCONOCIDO;
                            $cria->save();
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
            }

            $id_cria_principal = count($crias) > 0 ? $crias[0]->id_animal : null;

            $parto = PartoAnimal::create([
                'id_animal' => $madre->id_animal,
                'id_cria' => $id_cria_principal,
                'fecha_parto' => $request->fecha_parto,
                'tipo_parto' => $request->tipo_parto,
                'observaciones' => $request->observaciones,
                'padre' => $request->padre === 'otro' ? null : $request->padre,
                'padre_nombre' => $this->obtenerNombrePadre($request->padre),
            ]);

            foreach ($crias as $cria) {
                $parto->criasViaPivot()->attach($cria->id_animal);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'El parto ha sido registrado exitosamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar el parto: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error al registrar el parto. Por favor, inténtelo de nuevo.'
            ], 500);
        }
    }

    public function calcularDias(Request $request)
    {
        $request->validate([
            'id_animal' => 'required|exists:animales,id_animal'
        ]);

        $animal = Animal::find($request->id_animal);

        // Calcular días desde el último parto (si existe)
        $ultimoParto = $animal->partos()->latest('fecha_parto')->first();
        $diasUltimoParto = $ultimoParto
            ? (int) Carbon::parse($ultimoParto->fecha_parto)->diffInDays(Carbon::now())
            : 'N/A';

        // Calcular días de preñez (permitiendo valores negativos, pero mostrando el valor absoluto)
        if ($animal->estado_reproductivo_id == EstadoReproductivo::PRENADA) {
            $ultimoTacto = $animal->ultimoTacto()->first();
            if ($ultimoTacto) {
                // Se asume que la preñez inició 280 días antes de la fecha proyectada
                $fechaInicioPrenex = Carbon::parse($ultimoTacto->parto_proyectado)->subDays(280);
                // Calcula la diferencia en días, permitiendo negativos, y luego usamos abs() para mostrar el valor absoluto
                $diasPrenez = abs((int) Carbon::now()->diffInDays($fechaInicioPrenex, false));
            } else {
                $diasPrenez = 'N/A';
            }
        } else {
            $diasPrenez = 'N/A';
        }

        return response()->json([
            'diasUltimoParto' => $diasUltimoParto,
            'diasPrenez' => $diasPrenez,
        ]);
    }




    public function obtenerRazaPadre($idPadre)
    {
        if (!$idPadre) {
            return null;
        }

        $padre = Animal::find($idPadre);
        return $padre ? $padre->raza : null;
    }

    public function obtenerNombrePadre($idPadre)
    {
        if (!$idPadre) {
            return null;
        }

        $padre = Animal::find($idPadre);

        return $padre ? $padre->nombre : null;
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $predios = $user->predios()->pluck('predios.id')->toArray(); // Predios a los que el usuario tiene acceso

        // Validar los parámetros opcionales
        $request->validate([
            'predios' => 'nullable|array',
            'predios.*' => 'exists:predios,id',
            'fecha_parto' => 'nullable|date',
            'codigo_vaca' => 'nullable|string|max:255',
        ]);

        // Obtener filtros
        $predioSeleccionado = $request->input('predios', $predios);
        $fechaParto = $request->input('fecha_parto');
        $codigoVaca = $request->input('codigo_vaca');

        // Obtener vacas disponibles (solo en los predios seleccionados)
        $vacasDisponibles = Animal::where('sexo', 'hembra') // Solo hembras
            ->whereIn('id_predio', $predioSeleccionado)
            ->orderBy('codigo')
            ->get(['id_animal', 'codigo', 'nombre']);

        // Construir la consulta de partos
        $query = PartoAnimal::with(['madre', 'criasViaPivot'])
            ->whereHas('madre', function ($query) use ($predioSeleccionado) {
                $query->whereIn('id_predio', $predioSeleccionado);
            });

        // Filtrar por fecha de parto si está presente
        if ($fechaParto) {
            $query->whereDate('fecha_parto', $fechaParto);
        }

        // Filtrar por código de vaca si está presente
        if ($codigoVaca) {
            $query->whereHas('madre', function ($query) use ($codigoVaca) {
                $query->where('codigo', 'like', "%{$codigoVaca}%");
            });
        }

        // Ejecutar la consulta y obtener los resultados
        $partos = $query->orderBy('fecha_parto', 'desc')->paginate(10);

        // Retornar la vista con los resultados
        return view('inventario_animales.PartoHistorial', [
            'partos' => $partos,
            'prediosDisponibles' => $user->predios()->get(), // Selector de predios
            'vacasDisponibles' => $vacasDisponibles, // Selector de vacas
            'fechaParto' => $fechaParto,
            'codigoVaca' => $codigoVaca,
        ]);
    }
    public function getVacasByPredios(Request $request)
    {
        $request->validate([
            'predios' => 'required|array',
            'predios.*' => 'exists:predios,id',
        ]);

        // Filtrar animales por predios seleccionados
        $animales = Animal::whereIn('id_predio', $request->predios)
            ->select('id_animal', 'codigo', 'nombre')
            ->get();

        return response()->json(['animales' => $animales]);
    }

    /**
     * 2. Retorna todos los partos de una vaca específica en los predios seleccionados.
     */
    public function getPartosByVaca(Request $request)
    {
        $request->validate([
            'predios' => 'required|array',
            'predios.*' => 'exists:predios,id',
            'id_vaca' => 'required|exists:animales,id_animal',
        ]);

        $prediosSeleccionados = $request->input('predios');
        $idVaca = $request->input('id_vaca');

        // Buscar partos de la vaca en los predios seleccionados
        $partos = PartoAnimal::with(['madre', 'criasViaPivot'])
            ->where('id_animal', $idVaca)
            ->whereHas('madre', function ($query) use ($prediosSeleccionados) {
                $query->whereIn('id_predio', $prediosSeleccionados);
            })
            ->orderBy('fecha_parto', 'desc')
            ->get();

        return response()->json(['partos' => $partos]);
    }

    /**
     * 3. Retorna todos los partos dentro de un rango de fechas en los predios seleccionados.
     */
    public function getPartosByFecha(Request $request)
    {
        $request->validate([
            'predios' => 'required|array',
            'predios.*' => 'exists:predios,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $prediosSeleccionados = $request->input('predios');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        // Buscar partos en los predios seleccionados dentro del rango de fechas
        $partos = PartoAnimal::with(['madre', 'criasViaPivot'])
            ->whereBetween('fecha_parto', [$fechaInicio, $fechaFin])
            ->whereHas('madre', function ($query) use ($prediosSeleccionados) {
                $query->whereIn('id_predio', $prediosSeleccionados);
            })
            ->orderBy('fecha_parto', 'desc')
            ->get();

        return response()->json(['partos' => $partos]);
    }

    public function buscarPartos(Request $request)
    {
        $request->validate([
            'tipo_busqueda' => 'required|in:fecha,vaca',
            'predios' => 'required|array',
            'predios.*' => 'exists:predios,id',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
            'id_vaca' => 'nullable|exists:animales,id_animal',
        ]);

        $tipoBusqueda = $request->input('tipo_busqueda');
        $predios = $request->input('predios', []);
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $idVaca = $request->input('id_vaca');

        $query = PartoAnimal::with(['madre', 'criasViaPivot']);

        // Filtro por predios
        $query->whereHas('madre', function ($query) use ($predios) {
            $query->whereIn('id_predio', $predios);
        });

        // Filtros adicionales según tipo de búsqueda
        if ($tipoBusqueda === 'fecha') {
            $query->when($fechaInicio && $fechaFin, function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('fecha_parto', [$fechaInicio, $fechaFin]);
            })->when($fechaInicio && !$fechaFin, function ($q) use ($fechaInicio) {
                $q->whereDate('fecha_parto', '>=', $fechaInicio);
            })->when($fechaFin && !$fechaInicio, function ($q) use ($fechaFin) {
                $q->whereDate('fecha_parto', '<=', $fechaFin);
            });
        } elseif ($tipoBusqueda === 'vaca') {
            $query->where('id_animal', $idVaca);
        }

        // Obtener y transformar los datos
        $partos = $query->get()->map(function ($parto) {
            return [
                'id_parto' => $parto->id_parto,
                'tipo_parto' => $parto->tipo_parto,
                'fecha_parto' => $parto->fecha_parto,
                'madre' => $parto->madre ? $parto->madre->codigo : null,
                'crias' => $parto->criasViaPivot->map(fn($cria) => $cria->codigo)->toArray(),
            ];
        });

        // Respuesta
        if ($partos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron partos para los criterios seleccionados.',
                'partos' => [],
            ]);
        }

        return response()->json([
            'success' => true,
            'partos' => $partos,
        ]);
    }

    public function proyeccionParto()
    {
        try {
            // Obtener las vacas activas con el filtro aplicado
            $user = Auth::user();
            $vacas = Animal::filtrarPorEstadoYPredio($user)
                ->where('sexo', 'hembra') // Solo hembras
                ->whereNotIn('estado_productivo_id', [
                    EstadoProductivo::NOVILLA_VIENTRE,
                    EstadoProductivo::CRIA_HEMBRA,
                ]) // Excluir crías y novillas
                ->whereNotIn('estado_reproductivo_id', [
                    EstadoReproductivo::VACIA,
                    EstadoReproductivo::DESCONOCIDO,
                ])
                ->with(['ultimoTacto']) // Cargar relaciones necesarias
                ->get();

            // Mapear las vacas con su fecha de próximo parto proyectado
            $proyecciones = $vacas->map(function ($vaca) {
                $fechaProyeccion = null;
                $fechaPrenada = null;

                // Solo vacas con un tacto y resultado "Prenada"
                if ($vaca->ultimoTacto && $vaca->ultimoTacto->resultado === 'Prenada') {
                    $fechaProyeccion = $vaca->ultimoTacto->parto_proyectado;
                    $fechaPrenada = $vaca->ultimoTacto->fecha; // Asignar la fecha de preñez
                }

                // Retornar la información formateada solo si la vaca está preñada
                return $fechaProyeccion ? [
                    'id' => $vaca->id_animal,
                    'nombre' => $vaca->nombre,
                    'codigo' => $vaca->codigo,
                    'fecha_proyeccion' => $fechaProyeccion,
                    'fecha_prenada' => $fechaPrenada, // Agregar la fecha de preñez
                    'estado' => $vaca->estado_reproductivo_id,
                ] : null;
            });

            // Filtrar vacas con fechas proyectadas válidas
            $proyecciones = $proyecciones->filter(function ($proyeccion) {
                return !is_null($proyeccion);
            });

            // Cambiar la vista a 'reportes.proyeccionpartos'
            return view('reportes.proyeccionpartos', compact('proyecciones'));
        } catch (\Exception $e) {
            Log::error('Error al calcular las proyecciones de parto: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al calcular las proyecciones de parto.');
        }
    }


    public function diasAbiertos()
    {
        $user = Auth::user();

        // Obtener las hembras con los estados productivos específicos
        $hembras = Animal::where('sexo', 'hembra')
            ->whereIn('estado_productivo_id', [
                EstadoProductivo::VACA_PARIDA,
                EstadoProductivo::VACA_SECA,
                EstadoProductivo::NOVILLA_VIENTRE
            ])
            ->filtrarPorEstadoYPredio($user) // Método scope para filtrar por estado y predio
            ->with(['ultimoParto', 'ultimoTacto', 'estadoProductivo'])
            ->get();

        // Preparar datos adicionales para cada hembra
        $hembras = $hembras->map(function ($hembra) {
            $fechaParto = $hembra->ultimoParto ? \Carbon\Carbon::parse($hembra->ultimoParto->fecha_parto) : null;

            $fechaPrenez = $hembra->ultimoTacto && $hembra->ultimoTacto->fecha
                ? \Carbon\Carbon::parse($hembra->ultimoTacto->fecha)
                : null;


            $fechaUltimaPalpacion = $hembra->ultimoTacto && $hembra->ultimoTacto->fecha
                ? \Carbon\Carbon::parse($hembra->ultimoTacto->fecha)
                : null;
            $fechaActual = now();
            $diasAbiertos = null;

            // Clasificar y calcular días abiertos según el estado productivo
            switch ($hembra->estadoProductivo->id) {
                case EstadoProductivo::VACA_PARIDA:
                case EstadoProductivo::VACA_SECA:
                    if ($fechaParto) {
                        if ($fechaPrenez) {
                            // Cálculo entre la fecha del último parto y la fecha de preñez
                            $diasAbiertos = $fechaParto->diffInDays($fechaPrenez);
                        } else {
                            // Cálculo entre la fecha del último parto y la fecha actual
                            $diasAbiertos = $fechaParto->diffInDays($fechaActual);
                        }
                    }
                    break;

                case EstadoProductivo::NOVILLA_VIENTRE:
                    $fechaSeñorita = $hembra->fecha_nacimiento
                        ? \Carbon\Carbon::parse($hembra->fecha_nacimiento)->addMonths(24)
                        : null;
                    if ($fechaSeñorita) {
                        if ($fechaPrenez) {
                            // Cálculo entre la fecha en que cumplió 24 meses y la fecha de preñez
                            $diasAbiertos = $fechaSeñorita->diffInDays($fechaPrenez);
                        } else {
                            // Cálculo entre la fecha en que cumplió 24 meses y la fecha actual
                            $diasAbiertos = $fechaSeñorita->diffInDays($fechaActual);
                        }
                    }
                    break;
            }

            // Agregar datos calculados a la hembra
            $hembra->fecha_parto = $fechaParto ? $fechaParto->format('d/m/Y') : 'Sin registro';
            $hembra->fecha_prenez = $fechaPrenez ? $fechaPrenez->format('d/m/Y') : 'Sin registro';
            $hembra->dias_abiertos = $diasAbiertos !== null ? abs($diasAbiertos) : 'Sin calcular';
            $hembra->fecha_ultima_palpacion = $fechaUltimaPalpacion ? $fechaUltimaPalpacion->format('d/m/Y') : 'Sin registro';

            return $hembra;
        });

        return view('reportes.diasAbiertos', compact('hembras'));
    }


    public function downloadTemplate(Request $request)
{
    $request->validate([
        'predio_id' => 'required|exists:predios,id'
    ]);

    $predio_id = $request->predio_id;
    
    return Excel::download(
        new PartosTemplateExport($predio_id), 
        'plantilla_partos.xlsx'
    );
}

    /**
     * Importar partos desde Excel
     */
   public function import(Request $request)
{
    $request->validate([
        'predio_id' => 'required|exists:predios,id',
        'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
    ]);

    try {
        \Log::info('Inicio importación partos');

        $file = $request->file('file');
        $predio_id = $request->predio_id;

        $import = new PartosImport($predio_id);
        Excel::import($import, $file);

        $exitosos = $import->getExitosos();
        $duplicados = $import->getDuplicados();
        $errores = $import->getErrores();
        
        $totalDuplicados = count($duplicados);
        $totalErrores = count($errores);

        \Log::info("Importación completada: {$exitosos} exitosos, {$totalDuplicados} duplicados, {$totalErrores} errores");

        // Caso 1: SOLO errores y duplicados (0 exitosos) - CORREGIDO
        if ($exitosos == 0) {
            // Si no hay ni errores ni duplicados, el archivo estaba vacío
            if ($totalErrores == 0 && $totalDuplicados == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'El archivo no contiene registros válidos para importar',
                    'errores' => ['El archivo está vacío o no tiene datos válidos'],
                    'duplicados' => [],
                    'exitosos' => 0
                ], 422);
            }
            
            // Si hay errores o duplicados pero 0 exitosos
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo importar ningún parto nuevo',
                'errores' => $errores,
                'duplicados' => $duplicados,
                'exitosos' => 0
            ], 422);
        }

        // Caso 2: Algunos exitosos, hay errores o duplicados (importación parcial)
        if ($exitosos > 0 && ($totalErrores > 0 || $totalDuplicados > 0)) {
            return response()->json([
                'status' => 'partial',
                'message' => "Se importaron {$exitosos} parto(s) correctamente",
                'exitosos' => $exitosos,
                'duplicados' => $duplicados,
                'errores' => $errores
            ], 207);
        }

        // Caso 3: TODO exitoso (sin errores ni duplicados)
        return response()->json([
            'status' => 'success',
            'message' => "Se importaron {$exitosos} parto(s) exitosamente",
            'exitosos' => $exitosos,
            'duplicados' => [],
            'errores' => []
        ]);

    } catch (\Exception $e) {
        \Log::error('Error crítico en import: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        return response()->json([
            'status' => 'error',
            'message' => 'Error crítico al importar: ' . $e->getMessage(),
            'errores' => [$e->getMessage()],
            'duplicados' => [],
            'exitosos' => 0
        ], 500);
    }
}


}
