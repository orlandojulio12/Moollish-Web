<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Predios;
use App\Models\EstadoProductivo;
use Illuminate\Support\Facades\DB;


class ProductivosController extends Controller
{
    /* ===============================
       DASHBOARD PRODUCTIVOS
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
            'crias' => 0,
            'levantes_m' => 0,
            'levantes_h' => 0,
            'novillas_vientre' => 0,
            'toros' => 0,
            'toretes_ceba' => 0,
            'vacas_paridas' => 0,
            'vacas_secas' => 0,
        ];

        if ($predioId) {
            $base = Animal::where('id_predio', $predioId)
                ->where('estado_vida', Animal::ESTADO_VIVO);

            $contadores = [
                'crias' => (clone $base)->whereIn('estado_productivo_id', [
                    EstadoProductivo::CRIA_HEMBRA,
                    EstadoProductivo::CRIA_MACHO
                ])->count(),

                'levantes_m' => (clone $base)
                    ->where('estado_productivo_id', EstadoProductivo::MACHO_LEVANTE)
                    ->count(),

                'levantes_h' => (clone $base)
                    ->where('estado_productivo_id', EstadoProductivo::HEMBRA_LEVANTE)
                    ->count(),

                'novillas_vientre' => (clone $base)
                    ->where('estado_productivo_id', EstadoProductivo::NOVILLA_VIENTRE)
                    ->count(),

                'toros' => (clone $base)
                    ->where('estado_productivo_id', EstadoProductivo::REPRODUCTOR_TORO)
                    ->count(),

                'toretes_ceba' => (clone $base)
                    ->where('estado_productivo_id', EstadoProductivo::MACHO_CEBA)
                    ->count(),

                'vacas_paridas' => (clone $base)
                    ->where('estado_productivo_id', EstadoProductivo::VACA_PARIDA)
                    ->count(),

                'vacas_secas' => (clone $base)
                    ->where('estado_productivo_id', EstadoProductivo::VACA_SECA)
                    ->count(),
            ];
        }

        return view('productivos.index', compact(
            'user',
            'predios',
            'predioId',
            'contadores'
        ));
    }

    /* ===============================
       LISTADOS INDIVIDUALES
    =============================== */

    private function listado(Request $request, array $estados, string $titulo, string $descripcion)
    {
        $predioId = $request->predio_id;

        if (!$predioId) {
            return redirect()->route('productivos.index')
                ->with('error', 'Debe seleccionar un predio.');
        }

        $animales = Animal::where('id_predio', $predioId)
            ->where('estado_vida', Animal::ESTADO_VIVO)
            ->whereIn('estado_productivo_id', $estados)
            ->paginate(20);

        return view('productivos.listado', compact(
            'animales',
            'predioId',
            'titulo',
            'descripcion'
        ));
    }

    public function crias(Request $request)
    {
        return $this->listado(
            $request,
            [EstadoProductivo::CRIA_HEMBRA, EstadoProductivo::CRIA_MACHO],
            'Crías',
            'Animales en etapa de cría'
        );
    }

    public function levantesMachos(Request $request)
    {
        return $this->listado(
            $request,
            [EstadoProductivo::MACHO_LEVANTE],
            'Levantes Machos',
            'Machos en etapa de levante'
        );
    }

    public function levantesHembras(Request $request)
    {
        return $this->listado(
            $request,
            [EstadoProductivo::HEMBRA_LEVANTE],
            'Levantes Hembras',
            'Hembras en etapa de levante'
        );
    }

    public function novillasVientre(Request $request)
    {
        return $this->listado(
            $request,
            [EstadoProductivo::NOVILLA_VIENTRE],
            'Novillas de vientre',
            'Novillas reproductivas'
        );
    }

    public function toros(Request $request)
    {
        return $this->listado(
            $request,
            [EstadoProductivo::REPRODUCTOR_TORO],
            'Toros',
            'Reproductores activos'
        );
    }

    public function toretesCeba(Request $request)
    {
        return $this->listado(
            $request,
            [EstadoProductivo::MACHO_CEBA],
            'Toretes de ceba',
            'Machos en proceso de ceba'
        );
    }

    public function vacasParidas(Request $request)
    {
        return $this->listado(
            $request,
            [EstadoProductivo::VACA_PARIDA],
            'Vacas paridas',
            'Vacas con cría'
        );
    }

    public function vacasSecas(Request $request)
    {
        return $this->listado(
            $request,
            [EstadoProductivo::VACA_SECA],
            'Vacas secas',
            'Vacas en descanso productivo'
        );
    }
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
