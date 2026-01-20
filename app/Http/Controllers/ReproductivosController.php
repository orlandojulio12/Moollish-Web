<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Predios;
use App\Models\EstadoReproductivo;
use Illuminate\Support\Facades\DB;

class ReproductivosController extends Controller
{
    /* ===============================
       DASHBOARD REPRODUCTIVOS
    =============================== */

    public function index(Request $request)
    {
        $user = auth()->user();

        $predios = $user->role->name === 'admin'
            ? Predios::withCount(['animales' => function ($q) {
                $q->where('estado_vida', Animal::ESTADO_VIVO);
            }])->get()
            : $user->predios()->withCount(['animales' => function ($q) {
                $q->where('estado_vida', Animal::ESTADO_VIVO);
            }])->get();

        $predioId = $request->predio_id;

        $contadores = [
            'vacias'   => 0,
            'prenadas' => 0,
        ];

        if ($predioId) {
            $base = Animal::where('id_predio', $predioId)
                ->where('estado_vida', Animal::ESTADO_VIVO)
                ->where('sexo', 'hembra');

            $contadores = [
                'vacias' => (clone $base)
                    ->where('estado_reproductivo_id', EstadoReproductivo::VACIA)
                    ->count(),

                'prenadas' => (clone $base)
                    ->where('estado_reproductivo_id', EstadoReproductivo::PRENADA)
                    ->count(),
            ];
        }

        return view('reproductivos.index', compact(
            'user',
            'predios',
            'predioId',
            'contadores'
        ));
    }

    /* ===============================
       LISTADOS INDIVIDUALES
    =============================== */

    private function listado(
        Request $request,
        array $estados,
        string $titulo,
        string $descripcion
    ) {
        $predioId = $request->predio_id;

        if (!$predioId) {
            return redirect()->route('reproductivos.index')
                ->with('error', 'Debe seleccionar un predio.');
        }

        $animales = Animal::where('id_predio', $predioId)
            ->where('estado_vida', Animal::ESTADO_VIVO)
            ->where('sexo', 'hembra')
            ->whereIn('estado_reproductivo_id', $estados)
            ->paginate(20);

        return view('reproductivos.listado', compact(
            'animales',
            'predioId',
            'titulo',
            'descripcion'
        ));
    }

    /* ===============================
       VACÍAS
    =============================== */

    public function vacias(Request $request)
    {
        return $this->listado(
            $request,
            [EstadoReproductivo::VACIA],
            'Hembras vacías',
            'Hembras en estado reproductivo vacío'
        );
    }

    /* ===============================
       PREÑADAS
    =============================== */

    public function prenadas(Request $request)
    {
        return $this->listado(
            $request,
            [EstadoReproductivo::PRENADA],
            'Hembras preñadas',
            'Hembras en gestación'
        );
    }

    /* ===============================
       DETALLE ANIMAL (REUTILIZABLE)
    =============================== */

    public function detalleAnimal($id, Request $request)
    {
        $idPredio = $request->get('predio_id');

        $animal = DB::table('animales as a')
            ->leftJoin('lotes as lt', 'a.lote_id', '=', 'lt.id')
            ->leftJoin('potreros as pt', 'a.potrero_id', '=', 'pt.id')
            ->leftJoin('estado_productivo as ep', 'a.estado_productivo_id', '=', 'ep.id')
            ->leftJoin('estado_reproductivo as er', 'a.estado_reproductivo_id', '=', 'er.id')
            ->select([
                'a.id_animal',
                'a.codigo',
                'a.nombre',
                'a.identificacion_electronica',
                'a.fecha_nacimiento',
                'a.sexo',
                'a.hierro',
                'lt.nombre as lote',
                'pt.nombre as potrero',
                'ep.nombre as estado_productivo',
                'er.nombre as estado_reproductivo'
            ])
            ->where('a.id_animal', $id)
            ->where('a.id_predio', $idPredio)
            ->where('a.estado_vida', 1)
            ->first();

        if (!$animal) {
            return response()->json([
                'ok' => false,
                'message' => 'Animal no encontrado'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'data' => $animal
        ]);
    }
}
