<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            // Validar que se proporcione identificación
            $request->validate([
                'busqueda' => 'required|string'
            ]);

            $busqueda = $request->input('busqueda');

            // Buscar animal por codigo (único), nombre o identificación electrónica
            $animal = Animal::where('codigo', $busqueda)
                ->orWhere('nombre', 'LIKE', "%{$busqueda}%")
                ->orWhere('identificacion_electronica', 'LIKE', "%{$busqueda}%")
                ->first();

            if (!$animal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Animal no encontrado'
                ], 404);
            }

            $id_animal = $animal->id_animal;

            // Construir respuesta completa
            $historial = [
                // Información básica
                'id' => $id_animal,
                'codigo' => $animal->codigo,
                'nombre' => $animal->nombre,
                'identificacion_electronica' => $animal->identificacion_electronica,
                'sexo' => $animal->sexo,
                'nombre_raza' => $animal->raza ?? 'Sin raza',
                'color' => $animal->color,
                'fecha_nacimiento' => $animal->fecha_nacimiento,
                
                // Peso
                'ultimo_peso' => $this->obtenerUltimoPeso($id_animal),
                'fecha_ultimo_pesaje' => $this->obtenerFechaUltimoPesaje($id_animal),
                'peso_historico' => $this->obtenerPesoHistorico($id_animal),
                
                // Genealogía
                'madre' => $this->obtenerMadre($animal->madre),
                'padre' => $this->obtenerPadre($animal->padre),
                
                // Estados
                'estado_productivo' => $this->obtenerEstadoProductivo($id_animal),
                'estado_reproductivo' => $this->obtenerEstadoReproductivo($id_animal),
                
                // Crías
                'crias' => $this->obtenerCrias($id_animal),
                
                // Producción de leche
                'produccion_leche' => $this->obtenerProduccionLeche($id_animal),
                
                // Eventos reproductivos
                'montas' => $this->obtenerMontas($id_animal),
                'inseminaciones' => $this->obtenerInseminaciones($id_animal),
                'palpaciones' => $this->obtenerPalpaciones($id_animal),
                
                // Destetes/Secados
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
                'line' => $e->getLine(),
                'file' => $e->getFile()
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
}