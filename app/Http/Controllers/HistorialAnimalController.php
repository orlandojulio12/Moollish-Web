<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class HistorialAnimalController extends Controller
{
    /**
     * Obtener el historial completo de un animal
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerHistorialCompleto(Request $request)
{
    try {

        // ✅ Validar que venga el ID del animal
        $request->validate([
            'animal_id' => 'required|integer'
        ]);

        $animalId = $request->input('animal_id');

        // ✅ Buscar SOLO por ID (consulta exacta, no búsqueda textual)
        $animal = Animal::with(['potrero', 'lote'])
            ->where('id_animal', $animalId)
            ->first();

        if (!$animal) {
            return response()->json([
                'success' => false,
                'message' => 'Animal no encontrado'
            ], 404);
        }

        $id_animal = $animal->id_animal;

        // 🔹 Construir historial
        $historial = [
            'id' => $id_animal,
            'codigo' => $animal->codigo,
            'nombre' => $animal->nombre,
            'identificacion_electronica' => $animal->identificacion_electronica,
            'sexo' => $animal->sexo,
            'nombre_raza' => $animal->raza ?? 'Sin raza',
            'color' => $animal->color,
            'fecha_nacimiento' => $animal->fecha_nacimiento,

            'potrero' => $animal->potrero ? $animal->potrero->nombre : null,
            'lote' => $animal->lote ? $animal->lote->nombre : null,

            'ultimo_peso' => $this->obtenerUltimoPeso($id_animal),
            'fecha_ultimo_pesaje' => $this->obtenerFechaUltimoPesaje($id_animal),
            'peso_historico' => $this->obtenerPesoHistorico($id_animal),

            'madre' => $this->obtenerMadre($animal->madre),
            'padre' => $this->obtenerPadre($animal->padre),

            'estado_productivo' => $this->obtenerEstadoProductivo($id_animal),
            'estado_reproductivo' => $this->obtenerEstadoReproductivo($id_animal),

            'crias' => $this->obtenerCrias($id_animal),
            'produccion_leche' => $this->obtenerProduccionLeche($id_animal),

            'montas' => $this->obtenerMontas($id_animal),
            'inseminaciones' => $this->obtenerInseminaciones($id_animal),
            'palpaciones' => $this->obtenerPalpaciones($id_animal),

            'destetes' => $this->obtenerDestetes($id_animal),
        ];

        return response()->json([
            'success' => true,
            'data' => $historial
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener historial del animal',
            'error' => $e->getMessage(),
        ], 500);
    }
}
    /**
     * Obtener último peso del animal
     */
    private function obtenerUltimoPeso($animal_id)
    {
        $ultimoPeso = DB::table('pesaje_animal')
            ->where('id_animal', $animal_id)
            ->orderBy('fecha_pesaje', 'desc')
            ->first();

        return $ultimoPeso ? $ultimoPeso->peso : null;
    }

    /**
     * Obtener fecha del último pesaje
     */
    private function obtenerFechaUltimoPesaje($animal_id)
    {
        $ultimoPeso = DB::table('pesaje_animal')
            ->where('id_animal', $animal_id)
            ->orderBy('fecha_pesaje', 'desc')
            ->first();

        return $ultimoPeso ? $ultimoPeso->fecha_pesaje : null;
    }

    /**
     * Obtener historial de peso
     */
    private function obtenerPesoHistorico($animal_id)
    {
        return DB::table('pesaje_animal')
            ->where('id_animal', $animal_id)
            ->orderBy('fecha_pesaje', 'asc')
            ->select('fecha_pesaje', 'peso')
            ->get()
            ->map(function($pesaje) {
                return [
                    'fecha_pesaje' => $pesaje->fecha_pesaje,
                    'peso' => $pesaje->peso
                ];
            })
            ->toArray();
    }

    /**
     * Obtener información del padre
     */
    private function obtenerPadre($padre_id)
    {
        if (!$padre_id) return null;

        $padre = DB::table('animales')
            ->where('id_animal', $padre_id)
            ->select('codigo', 'nombre', 'raza', 'identificacion_electronica', 'sexo', 'fecha_nacimiento')
            ->first();

        if (!$padre) return null;

        return [
            'codigo' => $padre->codigo,
            'nombre' => $padre->nombre,
            'raza' => $padre->raza ?? 'Sin raza',
            'identificacion_electronica' => $padre->identificacion_electronica,
            'sexo' => $padre->sexo,
            'fecha_nacimiento' => $padre->fecha_nacimiento
        ];
    }

    /**
     * Obtener información de la madre
     */
    private function obtenerMadre($madre_id)
    {
        if (!$madre_id) return null;

        $madre = DB::table('animales')
            ->where('id_animal', $madre_id)
            ->select('codigo', 'nombre', 'raza', 'identificacion_electronica', 'sexo', 'fecha_nacimiento')
            ->first();

        if (!$madre) return null;

        return [
            'codigo' => $madre->codigo,
            'nombre' => $madre->nombre,
            'raza' => $madre->raza ?? 'Sin raza',
            'identificacion_electronica' => $madre->identificacion_electronica,
            'sexo' => $madre->sexo,
            'fecha_nacimiento' => $madre->fecha_nacimiento
        ];
    }

    /**
     * Obtener estado productivo actual
     */
    private function obtenerEstadoProductivo($animal_id)
    {
        // Primero intentar obtener desde la tabla animales directamente
        $animal = DB::table('animales')
            ->where('id_animal', $animal_id)
            ->first();

        if ($animal && $animal->estado_productivo_id) {
            $estado = DB::table('estado_productivo')
                ->where('id', $animal->estado_productivo_id)
                ->first();
            
            if ($estado) {
                // Buscar la fecha del último cambio de estado
                $ultimoCambio = DB::table('animal_estado_productivo')
                    ->where('id_animal', $animal_id)
                    ->orderBy('fecha_inicio', 'desc')
                    ->first();
                
                return [
                    'nombre' => $estado->nombre,
                    'fecha' => $ultimoCambio ? $ultimoCambio->fecha_inicio : null
                ];
            }
        }

        // Si no se encuentra, intentar desde la tabla animal_estado_productivo
        $estadoActual = DB::table('animal_estado_productivo')
            ->join('estado_productivo', 'animal_estado_productivo.id_estado_productivo', '=', 'estado_productivo.id')
            ->where('animal_estado_productivo.id_animal', $animal_id)
            ->orderBy('animal_estado_productivo.fecha_inicio', 'desc')
            ->select('estado_productivo.nombre', 'animal_estado_productivo.fecha_inicio as fecha')
            ->first();

        return $estadoActual ? [
            'nombre' => $estadoActual->nombre,
            'fecha' => $estadoActual->fecha
        ] : 'No definido';
    }

    /**
     * Obtener estado reproductivo actual
     */
    private function obtenerEstadoReproductivo($animal_id)
    {
        $estadoActual = DB::table('animal_estado_reproductivo')
            ->join('estado_reproductivo', 'animal_estado_reproductivo.id_estado_reproductivo', '=', 'estado_reproductivo.id')
            ->where('animal_estado_reproductivo.id_animal', $animal_id)
            ->orderBy('animal_estado_reproductivo.fecha_inicio', 'desc')
            ->select('estado_reproductivo.nombre', 'animal_estado_reproductivo.fecha_inicio as fecha')
            ->first();

        return $estadoActual ? [
            'nombre' => $estadoActual->nombre,
            'fecha' => $estadoActual->fecha
        ] : 'No definido';
    }

    /**
     * Obtener crías del animal
     */
    private function obtenerCrias($animal_id)
    {
        // Método 1: Buscar en parto_animal directamente (id_animal como madre)
        $crias = DB::table('parto_animal')
            ->join('animales', 'parto_animal.id_cria', '=', 'animales.id_animal')
            ->where('parto_animal.id_animal', $animal_id)
            ->select(
                'animales.codigo',
                'animales.nombre',
                'animales.fecha_nacimiento',
                'animales.sexo',
                'parto_animal.fecha_parto',
                'parto_animal.tipo_parto',
                'parto_animal.observaciones'
            )
            ->get();

        // Si no encuentra crías, intentar Método 2: Usando la tabla pivot parto_cria
        if ($crias->isEmpty()) {
            $crias = DB::table('parto_animal')
                ->join('parto_cria', 'parto_animal.id_parto', '=', 'parto_cria.id_parto')
                ->join('animales', 'parto_cria.id_cria', '=', 'animales.id_animal')
                ->where('parto_animal.id_animal', $animal_id)
                ->select(
                    'animales.codigo',
                    'animales.nombre',
                    'animales.fecha_nacimiento',
                    'animales.sexo',
                    'parto_animal.fecha_parto',
                    'parto_animal.tipo_parto',
                    'parto_animal.observaciones'
                )
                ->get();
        }

        // Si aún no encuentra, intentar Método 3: Buscar animales cuya madre sea este animal
        if ($crias->isEmpty()) {
            $crias = DB::table('animales')
                ->where('madre', $animal_id)
                ->select(
                    'codigo',
                    'nombre',
                    'fecha_nacimiento',
                    'sexo'
                )
                ->get()
                ->map(function($cria) {
                    return [
                        'codigo' => $cria->codigo,
                        'nombre' => $cria->nombre,
                        'fecha_nacimiento' => $cria->fecha_nacimiento,
                        'sexo' => $cria->sexo,
                        'fecha_parto' => $cria->fecha_nacimiento, // Usar fecha_nacimiento como fecha_parto
                        'tipo_parto' => null,
                        'observaciones' => null
                    ];
                });
        }

        return $crias->toArray();
    }

    /**
     * Obtener producción de leche
     */
    private function obtenerProduccionLeche($animal_id)
    {
        return DB::table('pesaje_leche_animal')
            ->where('id_animal', $animal_id)
            ->orderBy('fecha_pesaje', 'desc')
            ->select('fecha_pesaje', 'dias_parida', 'total_pesaje')
            ->get()
            ->map(function($pesaje) {
                return [
                    'fecha_pesaje' => $pesaje->fecha_pesaje,
                    'dias_parida' => $pesaje->dias_parida,
                    'total_pesaje' => $pesaje->total_pesaje
                ];
            })
            ->toArray();
    }

    /**
     * Obtener montas naturales
     */
    private function obtenerMontas($animal_id)
    {
        return DB::table('monta_natural')
            ->leftJoin('animales as toros', 'monta_natural.id_toro', '=', 'toros.id_animal')
            ->where('monta_natural.id_vaca', $animal_id)
            ->orderBy('monta_natural.fecha_monta', 'desc')
            ->select(
                'monta_natural.fecha_monta',
                'toros.codigo as toro_codigo',
                'toros.nombre as toro_nombre',
                'monta_natural.id_toro'
            )
            ->get()
            ->map(function($monta) {
                return [
                    'fecha_monta' => $monta->fecha_monta,
                    'toro_codigo' => $monta->toro_codigo ?? 'ID: ' . $monta->id_toro,
                    'toro_nombre' => $monta->toro_nombre ?? 'Desconocido'
                ];
            })
            ->toArray();
    }

    /**
     * Obtener inseminaciones
     */
    private function obtenerInseminaciones($animal_id)
    {
        return DB::table('inseminacion')
            ->leftJoin('pajillas_semen', 'inseminacion.id_pajilla', '=', 'pajillas_semen.id')
            ->where('inseminacion.id_vaca', $animal_id)
            ->orderBy('inseminacion.fecha_inseminacion', 'desc')
            ->select(
                'inseminacion.fecha_inseminacion',
                'inseminacion.id_pajilla',
                'pajillas_semen.codigo_pajilla',
                'pajillas_semen.nombre_reproductor'
            )
            ->get()
            ->map(function($insem) {
                return [
                    'fecha_inseminacion' => $insem->fecha_inseminacion,
                    'id_pajilla' => $insem->id_pajilla,
                    'codigo_pajilla' => $insem->codigo_pajilla ?? 'N/A',
                    'nombre_reproductor' => $insem->nombre_reproductor ?? 'No especificado'
                ];
            })
            ->toArray();
    }

    /**
     * Obtener palpaciones
     */
    private function obtenerPalpaciones($animal_id)
    {
        return DB::table('palpaciones')
            ->where('id_animal', $animal_id)
            ->orderBy('fecha', 'desc')
            ->select('fecha', 'resultado', 'diagnostico', 'parto_proyectado')
            ->get()
            ->map(function($palp) {
                return [
                    'fecha' => $palp->fecha,
                    'resultado' => $palp->resultado,
                    'diagnostico' => $palp->diagnostico,
                    'parto_proyectado' => $palp->parto_proyectado
                ];
            })
            ->toArray();
    }

    /**
     * Obtener destetes/secados
     */
    private function obtenerDestetes($animal_id)
    {
        return DB::table('destetes')
            ->leftJoin('animales as crias', 'destetes.id_cria_animal', '=', 'crias.id_animal')
            ->where('destetes.id_animal', $animal_id)
            ->orderBy('destetes.fecha_destete', 'desc')
            ->select(
                'destetes.id_cria_animal',
                'crias.codigo as cria_codigo',
                'crias.nombre as cria_nombre',
                'destetes.is_cria_levante',
                'destetes.vaca_secado',
                'destetes.peso_cria',
                'destetes.peso_vaca',
                'destetes.fecha_destete',
                'destetes.motivo',
                'destetes.observacion'
            )
            ->get()
            ->map(function($destete) {
                return [
                    'id_cria_animal' => $destete->id_cria_animal,
                    'cria_codigo' => $destete->cria_codigo ?? 'N/A',
                    'cria_nombre' => $destete->cria_nombre ?? 'N/A',
                    'is_cria_levante' => $destete->is_cria_levante, // 0 o 1
                    'cria_destetada' => $destete->is_cria_levante == 1 ? 'Sí' : 'No',
                    'vaca_secado' => $destete->vaca_secado, // 0 o 1
                    'vaca_paso_levante' => $destete->vaca_secado == 1 ? 'Sí' : 'No',
                    'peso_cria' => $destete->peso_cria,
                    'peso_vaca' => $destete->peso_vaca,
                    'fecha_destete' => $destete->fecha_destete,
                    'motivo' => $destete->motivo,
                    'observacion' => $destete->observacion
                ];
            })
            ->toArray();
    }

    /**
     * Buscar animales por término de búsqueda (codigo, nombre o chip)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarAnimales(Request $request)
    {
        try {
            $busqueda = $request->input('busqueda', '');

            $animales = Animal::where('codigo', 'LIKE', "%{$busqueda}%")
                ->orWhere('nombre', 'LIKE', "%{$busqueda}%")
                ->orWhere('identificacion_electronica', 'LIKE', "%{$busqueda}%")
                ->limit(10)
                ->get(['id_animal as id', 'nombre', 'codigo', 'identificacion_electronica']);

            return response()->json([
                'success' => true,
                'data' => $animales
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar animales',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // =========================================================================
    // REPORTE 1: PREÑECES CON DÍAS DE GESTACIÓN
    // GET /api/reportes/preneces?predio_id=25&lote_id=7
    // =========================================================================
 
    public function preneces(Request $request)
    {
        try {
            $request->validate([
                'predio_id' => 'required|integer',
                'lote_id'   => 'nullable|integer',
            ]);
 
            $predioId = $request->input('predio_id');
            $loteId   = $request->input('lote_id');
 
            // ── 1. Días de gestación configurados para este predio ────────────
            $paramGestacion = DB::table('parametro_dias_gestacion')
                ->where('predio_id', $predioId)
                ->first();
            $diasGestacionEsperados = $paramGestacion ? $paramGestacion->dias_gestacion : 280;
 
            // ── 2. Traer animales preñadas del predio ─────────────────────────
            //    Usamos estado_reproductivo_id = 1 (Preñada) igual que el historial
            $query = DB::table('animales')
                ->leftJoin('lotes',    'animales.lote_id',    '=', 'lotes.id')
                ->leftJoin('potreros', 'animales.potrero_id', '=', 'potreros.id')
                ->where('animales.id_predio', $predioId)
                ->where('animales.estado_reproductivo_id', 1)   // 1 = Preñada
                ->where('animales.estado_vida', 1)
                ->select(
                    'animales.id_animal',
                    'animales.codigo',
                    'animales.nombre',
                    'animales.raza',
                    'animales.sexo',
                    'animales.fecha_nacimiento',
                    'lotes.nombre    as lote_nombre',
                    'potreros.nombre as potrero_nombre'
                );
 
            if ($loteId) {
                $query->where('animales.lote_id', $loteId);
            }
 
            $animales = $query->get();
 
            // ── 3. Para cada animal, calcular gestación ───────────────────────
            $resultado = $animales->map(function ($animal) use ($diasGestacionEsperados) {
 
                $idAnimal = $animal->id_animal;
 
                // Última inseminación
                $ultimaInseminacion = DB::table('inseminacion')
                    ->where('id_vaca', $idAnimal)
                    ->orderBy('fecha_inseminacion', 'desc')
                    ->value('fecha_inseminacion');
 
                // Última monta natural
                $ultimaMonta = DB::table('monta_natural')
                    ->where('id_vaca', $idAnimal)
                    ->orderBy('fecha_monta', 'desc')
                    ->value('fecha_monta');
 
                // Última palpación con resultado Preñada
                $ultimaPalpacion = DB::table('palpaciones')
                    ->where('id_animal', $idAnimal)
                    ->orderBy('fecha', 'desc')
                    ->select('fecha', 'resultado', 'diagnostico', 'parto_proyectado')
                    ->first();
 
                // Determinar fecha de concepción más reciente entre inseminación y monta
                $fechaConcepcion = null;
                $tipoConcepcion  = null;
 
                if ($ultimaInseminacion && $ultimaMonta) {
                    if ($ultimaInseminacion >= $ultimaMonta) {
                        $fechaConcepcion = $ultimaInseminacion;
                        $tipoConcepcion  = 'Inseminación';
                    } else {
                        $fechaConcepcion = $ultimaMonta;
                        $tipoConcepcion  = 'Monta natural';
                    }
                } elseif ($ultimaInseminacion) {
                    $fechaConcepcion = $ultimaInseminacion;
                    $tipoConcepcion  = 'Inseminación';
                } elseif ($ultimaMonta) {
                    $fechaConcepcion = $ultimaMonta;
                    $tipoConcepcion  = 'Monta natural';
                }
 
                // Calcular días de gestación transcurridos
                $diasTranscurridos  = null;
                $porcentajeAvance   = null;
                $partoProyectado    = null;
                $diasParaParto      = null;
 
                if ($fechaConcepcion) {
                    $inicio            = Carbon::parse($fechaConcepcion);
                    $hoy               = Carbon::today();
                    $diasTranscurridos = $inicio->diffInDays($hoy);
                    $porcentajeAvance  = round(($diasTranscurridos / $diasGestacionEsperados) * 100, 1);
 
                    // Parto proyectado: preferir el de palpación si existe, sino calcular
                    if ($ultimaPalpacion && $ultimaPalpacion->parto_proyectado) {
                        $partoProyectado = $ultimaPalpacion->parto_proyectado;
                    } else {
                        $partoProyectado = $inicio->copy()
                            ->addDays($diasGestacionEsperados)
                            ->format('Y-m-d');
                    }
 
                    $diasParaParto = Carbon::today()->diffInDays(
                        Carbon::parse($partoProyectado),
                        false   // false = devuelve negativo si ya pasó
                    );
                }
 
                return [
                    'id_animal'                   => $idAnimal,
                    'codigo'                      => $animal->codigo,
                    'nombre'                      => $animal->nombre ?? 'Sin nombre',
                    'raza'                        => $animal->raza  ?? 'Sin raza',
                    'lote'                        => $animal->lote_nombre,
                    'potrero'                     => $animal->potrero_nombre,
                    'fecha_concepcion'            => $fechaConcepcion,
                    'tipo_concepcion'             => $tipoConcepcion,
                    'dias_gestacion_transcurridos'=> $diasTranscurridos,
                    'dias_gestacion_esperados'    => $diasGestacionEsperados,
                    'porcentaje_gestacion'        => $porcentajeAvance,
                    'parto_proyectado'            => $partoProyectado,
                    'dias_para_parto'             => $diasParaParto,
                    'ultima_palpacion'            => $ultimaPalpacion ? [
                        'fecha'           => $ultimaPalpacion->fecha,
                        'resultado'       => $ultimaPalpacion->resultado,
                        'diagnostico'     => $ultimaPalpacion->diagnostico,
                        'parto_proyectado'=> $ultimaPalpacion->parto_proyectado,
                    ] : null,
                ];
            });
 
            // ── 4. Ordenar: primero las que están más cerca de parir ──────────
            $ordenado = $resultado
                ->sortBy(fn($a) => $a['dias_para_parto'] ?? PHP_INT_MAX)
                ->values();
 
            // ── 5. Resumen ────────────────────────────────────────────────────
            $proximasA30 = $ordenado->filter(
                fn($a) => $a['dias_para_parto'] !== null
                       && $a['dias_para_parto'] >= 0
                       && $a['dias_para_parto'] <= 30
            )->count();
 
            $promedioGestacion = $ordenado
                ->whereNotNull('dias_gestacion_transcurridos')
                ->avg('dias_gestacion_transcurridos');
 
            return response()->json([
                'success' => true,
                'data'    => $ordenado,
                'resumen' => [
                    'total_prenadas'              => $ordenado->count(),
                    'proximas_a_parir_30_dias'    => $proximasA30,
                    'promedio_dias_gestacion'     => $promedioGestacion ? round($promedioGestacion) : null,
                    'dias_gestacion_configurados' => $diasGestacionEsperados,
                ],
            ], 200);
 
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar reporte de preñeces',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
 
 
    // =========================================================================
    // REPORTE 2: GANANCIA MENSUAL EN PESO — POR LOTE O INDIVIDUAL
    // GET /api/reportes/ganancia-peso?predio_id=25&lote_id=7&meses=6
    // GET /api/reportes/ganancia-peso?predio_id=25&id_animal=82&meses=12
    // =========================================================================
 
    public function gananciaPeso(Request $request)
    {
        try {
            $request->validate([
                'predio_id' => 'required|integer',
                'lote_id'   => 'nullable|integer',
                'id_animal' => 'nullable|integer',
                'meses'     => 'nullable|integer|min:1|max:36',
            ]);
 
            $predioId = $request->input('predio_id');
            $loteId   = $request->input('lote_id');
            $animalId = $request->input('id_animal');
            $meses    = $request->input('meses', 6);
 
            // ── Modo INDIVIDUAL ───────────────────────────────────────────────
            if ($animalId) {
                return $this->gananciaPesoIndividual($animalId, $meses);
            }
 
            // ── Modo LOTE / PREDIO ────────────────────────────────────────────
            return $this->gananciaPesoPorLote($predioId, $loteId, $meses);
 
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar reporte de ganancia de peso',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
 
    // ─────────────────────────────────────────────────────────────────────────
    // GANANCIA INDIVIDUAL
    // ─────────────────────────────────────────────────────────────────────────
    private function gananciaPesoIndividual($animalId, $meses)
    {
        // Datos básicos del animal
        $animal = DB::table('animales')
            ->leftJoin('lotes', 'animales.lote_id', '=', 'lotes.id')
            ->where('animales.id_animal', $animalId)
            ->select(
                'animales.id_animal',
                'animales.codigo',
                'animales.nombre',
                'animales.raza',
                'lotes.nombre as lote_nombre'
            )
            ->first();
 
        if (!$animal) {
            return response()->json([
                'success' => false,
                'message' => 'Animal no encontrado',
            ], 404);
        }
 
        // Todos los pesajes del animal ordenados por fecha
        $pesajes = DB::table('pesaje_animal')
            ->where('id_animal', $animalId)
            ->whereNotNull('fecha_pesaje')
            ->where('fecha_pesaje', '>=', Carbon::now()->subMonths($meses)->format('Y-m-d'))
            ->orderBy('fecha_pesaje', 'asc')
            ->select('fecha_pesaje', 'peso')
            ->get();
 
        if ($pesajes->isEmpty()) {
            return response()->json([
                'success' => true,
                'modo'    => 'individual',
                'animal'  => $animal,
                'data'    => [],
                'resumen' => ['mensaje' => 'Sin pesajes registrados en el período'],
            ]);
        }
 
        // Calcular ganancia entre pesajes consecutivos
        $detalle = [];
        $pesajesArray = $pesajes->values()->all();
 
        for ($i = 0; $i < count($pesajesArray); $i++) {
            $actual   = $pesajesArray[$i];
            $anterior = $i > 0 ? $pesajesArray[$i - 1] : null;
 
            $gananciaKg    = null;
            $diasEntrePes  = null;
            $gananciaDiaria = null;
 
            if ($anterior) {
                $gananciaKg    = round($actual->peso - $anterior->peso, 2);
                $diasEntrePes  = Carbon::parse($anterior->fecha_pesaje)
                                       ->diffInDays(Carbon::parse($actual->fecha_pesaje));
                $gananciaDiaria = $diasEntrePes > 0
                    ? round($gananciaKg / $diasEntrePes, 3)
                    : null;
            }
 
            $detalle[] = [
                'fecha_pesaje'    => $actual->fecha_pesaje,
                'periodo'         => Carbon::parse($actual->fecha_pesaje)->format('Y-m'),
                'peso'            => $actual->peso,
                'ganancia_kg'     => $gananciaKg,
                'dias_entre_pesajes' => $diasEntrePes,
                'ganancia_diaria_kg' => $gananciaDiaria,
            ];
        }
 
        // Agrupar ganancia por mes
        $porMes = collect($detalle)
            ->groupBy('periodo')
            ->map(function ($items, $periodo) {
                $gananciaMes = collect($items)->whereNotNull('ganancia_kg')->sum('ganancia_kg');
                $pesoFinal   = collect($items)->last()['peso'];
                $pesajes     = count($items);
                return [
                    'periodo'      => $periodo,
                    'ganancia_kg'  => round($gananciaMes, 2),
                    'peso_al_cierre' => $pesoFinal,
                    'pesajes_en_el_mes' => $pesajes,
                ];
            })
            ->values();
 
        // Resumen general
        $pesoInicial      = $pesajesArray[0]->peso;
        $pesoFinal        = end($pesajesArray)->peso;
        $gananciaTotalKg  = round($pesoFinal - $pesoInicial, 2);
        $diasTotales      = Carbon::parse($pesajesArray[0]->fecha_pesaje)
                                  ->diffInDays(Carbon::parse(end($pesajesArray)->fecha_pesaje));
 
        return response()->json([
            'success' => true,
            'modo'    => 'individual',
            'animal'  => [
                'id_animal'  => $animal->id_animal,
                'codigo'     => $animal->codigo,
                'nombre'     => $animal->nombre,
                'raza'       => $animal->raza,
                'lote'       => $animal->lote_nombre,
            ],
            'data'    => $detalle,
            'por_mes' => $porMes,
            'resumen' => [
                'peso_inicial'           => $pesoInicial,
                'peso_final'             => $pesoFinal,
                'ganancia_total_kg'      => $gananciaTotalKg,
                'dias_totales'           => $diasTotales,
                'ganancia_diaria_promedio_kg' => $diasTotales > 0
                    ? round($gananciaTotalKg / $diasTotales, 3)
                    : null,
                'total_pesajes'          => count($pesajesArray),
            ],
        ]);
    }
 
    // ─────────────────────────────────────────────────────────────────────────
    // GANANCIA POR LOTE
    // ─────────────────────────────────────────────────────────────────────────
    private function gananciaPesoPorLote($predioId, $loteId, $meses)
    {
        // Traer animales del predio/lote
        $queryAnimales = DB::table('animales')
            ->leftJoin('lotes', 'animales.lote_id', '=', 'lotes.id')
            ->where('animales.id_predio', $predioId)
            ->where('animales.estado_vida', 1)
            ->select(
                'animales.id_animal',
                'animales.codigo',
                'animales.nombre',
                'animales.raza',
                'animales.lote_id',
                'lotes.nombre as lote_nombre'
            );
 
        if ($loteId) {
            $queryAnimales->where('animales.lote_id', $loteId);
        }
 
        $animales = $queryAnimales->get();
 
        if ($animales->isEmpty()) {
            return response()->json([
                'success' => true,
                'modo'    => 'lote',
                'data'    => [],
                'resumen' => ['mensaje' => 'No se encontraron animales con los filtros indicados'],
            ]);
        }
 
        $fechaDesde = Carbon::now()->subMonths($meses)->format('Y-m-d');
 
        // Para cada animal, obtener sus pesajes en el período
        $resultadoPorLote = [];
 
        foreach ($animales as $animal) {
            $pesajes = DB::table('pesaje_animal')
                ->where('id_animal', $animal->id_animal)
                ->whereNotNull('fecha_pesaje')
                ->where('fecha_pesaje', '>=', $fechaDesde)
                ->orderBy('fecha_pesaje', 'asc')
                ->select('fecha_pesaje', 'peso')
                ->get();
 
            if ($pesajes->count() < 2) {
                continue; // Necesitamos al menos 2 pesajes para calcular ganancia
            }
 
            // Agrupar los pesajes por mes: primer y último peso de cada mes
            $pesosPorMes = $pesajes
                ->groupBy(fn($p) => Carbon::parse($p->fecha_pesaje)->format('Y-m'))
                ->map(function ($items, $periodo) {
                    return [
                        'periodo'       => $periodo,
                        'primer_peso'   => $items->first()->peso,
                        'ultimo_peso'   => $items->last()->peso,
                        'primer_fecha'  => $items->first()->fecha_pesaje,
                        'ultimo_fecha'  => $items->last()->fecha_pesaje,
                        'num_pesajes'   => $items->count(),
                    ];
                })
                ->values()
                ->all();
 
            // Ganancia entre meses consecutivos
            for ($i = 0; $i < count($pesosPorMes); $i++) {
                $mes      = $pesosPorMes[$i];
                $loteKey  = $animal->lote_id ?? 'sin_lote';
                $periodo  = $mes['periodo'];
 
                if (!isset($resultadoPorLote[$loteKey])) {
                    $resultadoPorLote[$loteKey] = [
                        'lote_id'    => $animal->lote_id,
                        'lote_nombre'=> $animal->lote_nombre ?? 'Sin lote',
                        'meses'      => [],
                    ];
                }
 
                if (!isset($resultadoPorLote[$loteKey]['meses'][$periodo])) {
                    $resultadoPorLote[$loteKey]['meses'][$periodo] = [
                        'periodo'           => $periodo,
                        'suma_pesos'        => 0,
                        'suma_ganancias'    => 0,
                        'cantidad_animales' => 0,
                        'animales'          => [],
                    ];
                }
 
                // Ganancia del animal en este mes: último peso del mes menos el primero
                $gananciaMes = round($mes['ultimo_peso'] - $mes['primer_peso'], 2);
 
                $resultadoPorLote[$loteKey]['meses'][$periodo]['suma_pesos']        += $mes['ultimo_peso'];
                $resultadoPorLote[$loteKey]['meses'][$periodo]['suma_ganancias']    += $gananciaMes;
                $resultadoPorLote[$loteKey]['meses'][$periodo]['cantidad_animales'] += 1;
                $resultadoPorLote[$loteKey]['meses'][$periodo]['animales'][]         = [
                    'id_animal'    => $animal->id_animal,
                    'codigo'       => $animal->codigo,
                    'nombre'       => $animal->nombre,
                    'peso_inicio'  => $mes['primer_peso'],
                    'peso_cierre'  => $mes['ultimo_peso'],
                    'ganancia_kg'  => $gananciaMes,
                ];
            }
        }
 
        // Formatear respuesta final
        $data = collect($resultadoPorLote)->map(function ($lote) {
            $mesesFormateados = collect($lote['meses'])
                ->sortKeys()
                ->map(function ($mes) {
                    $cant = $mes['cantidad_animales'];
                    return [
                        'periodo'               => $mes['periodo'],
                        'cantidad_animales'     => $cant,
                        'peso_promedio_mes'     => $cant > 0 ? round($mes['suma_pesos'] / $cant, 2) : null,
                        'ganancia_promedio_kg'  => $cant > 0 ? round($mes['suma_ganancias'] / $cant, 2) : null,
                        'ganancia_total_lote_kg'=> round($mes['suma_ganancias'], 2),
                        'animales'              => $mes['animales'],
                    ];
                })
                ->values();
 
            // Resumen del lote en el período
            $totalGanancia     = $mesesFormateados->sum('ganancia_total_lote_kg');
            $mejorMes          = $mesesFormateados->sortByDesc('ganancia_promedio_kg')->first();
 
            return [
                'lote_id'     => $lote['lote_id'],
                'lote_nombre' => $lote['lote_nombre'],
                'meses'       => $mesesFormateados,
                'resumen_lote' => [
                    'ganancia_total_periodo_kg'    => round($totalGanancia, 2),
                    'ganancia_promedio_mensual_kg' => $mesesFormateados->count() > 0
                        ? round($mesesFormateados->avg('ganancia_promedio_kg'), 2)
                        : null,
                    'mejor_mes'                    => $mejorMes['periodo'] ?? null,
                    'meses_con_datos'              => $mesesFormateados->count(),
                ],
            ];
        })->values();
 
        // Resumen global
        $totalAnimalesConDatos = $animales->count();
        $gananciaTotalGeneral  = $data->sum(fn($l) => $l['resumen_lote']['ganancia_total_periodo_kg']);
 
        return response()->json([
            'success' => true,
            'modo'    => 'lote',
            'data'    => $data,
            'resumen' => [
                'total_animales'            => $totalAnimalesConDatos,
                'ganancia_total_periodo_kg' => round($gananciaTotalGeneral, 2),
                'periodo_analizado_meses'   => $meses,
                'fecha_desde'               => $fechaDesde,
            ],
        ]);
    }
}