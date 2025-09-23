<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecadoDestete;
use App\Models\Animal;
use App\Models\EstadoReproductivo;
use App\Models\EstadoProductivo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\AnimalEstadoProductivo;
use App\Models\AnimalEstadoReproductivo;

class SecadoDesteteController extends Controller
{
    /**
     * Constructor para aplicar middleware de autenticación.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();
        $predios = $usuario->predios;
        if ($usuario->role->name !== 'admin') {
            // Obtener los IDs de los predios asociados al usuario
            $predioIdsUsuario = $usuario->predios->pluck('id')->toArray();
            // Obtener las vacas que pertenecen a los predios del usuario,
            // que sean hembras y que estén en estado reproductivo "prenada" o estado productivo "Vaca Parida"
            $vacas = Animal::where('sexo', 'hembra') // Vacas hembras
                ->whereIn('id_predio', $predioIdsUsuario) // Predios del usuario
                ->where(function ($query) {
                    $query->where('estado_reproductivo_id', 1) // Estado reproductivo "prenada"
                        ->orWhere('estado_productivo_id', 1); // Estado productivo "Vaca Parida"
                })
                ->with(['criasViaPivot']) // Cargar relaciones necesarias
                ->get();
        } else {
            // Si el usuario es admin, obtener todas las vacas hembras que estén en estado reproductivo "prenada" o estado productivo "Vaca Parida"
            $vacas = Animal::where('sexo', 'hembra') // Vacas hembras
                ->where(function ($query) {
                    $query->where('estado_reproductivo_id', 1) // Estado reproductivo "prenada"
                        ->orWhere('estado_productivo_id', 1); // Estado productivo "Vaca Parida"
                })
                ->with(['criasViaPivot']) // Cargar relaciones necesarias
                ->get();
        }
        return view('inventario_animales.SecadoDestete', compact('vacas', 'predios'));
    }

    public function getVacaDetails(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'id_animal' => 'required|exists:animales,id_animal',
        ]);

        $idVaca = $request->input('id_animal');
        $usuario = Auth::user();

        if ($usuario->role->name !== 'admin') {
            $predioIdsUsuario = $usuario->predios->pluck('id')->toArray();

            $vaca = Animal::where('id_animal', $idVaca)
                ->whereIn('id_predio', $predioIdsUsuario)
                ->where(function ($query) {
                    $query->where('estado_reproductivo_id', 1)
                        ->orWhere('estado_productivo_id', 1);
                })
                ->with([
                    'predio',
                    'potrero',
                    'lote',
                    'ultimoParto',
                    'pesajeLeches',
                    'criasViaPivot.ultimoEstadoReproductivo.estadoReproductivo',
                    'ultimoEstadoReproductivo.estadoReproductivo',
                    'ultimoEstadoProductivo.estadoProductivo',
                    'partos',
                ])
                ->first();
        } else {
            $vaca = Animal::where('id_animal', $idVaca)
                ->where('sexo', 'hembra')
                ->where(function ($query) {
                    $query->where('estado_reproductivo_id', 1)
                        ->orWhere('estado_productivo_id', 1);
                })
                ->with([
                    'predio',
                    'potrero',
                    'lote',
                    'ultimoParto',
                    'pesajeLeches',
                    'criasViaPivot.ultimoEstadoReproductivo.estadoReproductivo',
                    'ultimoEstadoReproductivo.estadoReproductivo',
                    'partos',
                ])
                ->firstOrFail();
        }

        if (!$vaca) {
            return response()->json([
                'success' => false,
                'message' => 'Vaca no encontrada o no autorizada.',
            ], 403);
        }

        // Calcular 'dias_prenez' basado en el último estado reproductivo
        $ultimoEstadoReproductivo = $vaca->ultimoEstadoReproductivo;

        if ($ultimoEstadoReproductivo && $ultimoEstadoReproductivo->estadoReproductivo->id == 1) { // Prenada
            $fechaEstado = $ultimoEstadoReproductivo->fecha_inicio instanceof Carbon
                ? $ultimoEstadoReproductivo->fecha_inicio
                : Carbon::parse($ultimoEstadoReproductivo->fecha_inicio);

            if ($fechaEstado->lte(now())) {
                $diasPrenez = (int) $fechaEstado->diffInDays(now());
                $tiempoPrenez = $this->calcularTiempoLegible($diasPrenez);
            } else {
                $diasPrenez = 0;
                $tiempoPrenez = '0 días';
            }

            $fechaEstadoFormatted = $fechaEstado->format('Y-m-d');
        } else {
            $diasPrenez = 'Vaca no preñada';
            $tiempoPrenez = 'Vaca no preñada';
            $fechaEstadoFormatted = 'N/A';
        }

        // Calcular 'total_leche' sumando 'total_pesaje' de PesajeLeche
        $totalLeche = $vaca->pesajeLeches()->sum('total_pesaje') ?? 0;

        // Obtener la fecha del último parto
        $ultimoParto = $vaca->partos()->latest('fecha_parto')->first();
        $fechaUltimoParto = $ultimoParto && $ultimoParto->fecha_parto
            ? $ultimoParto->fecha_parto
            : 'Esta vaca nunca ha parido';

        // Obtener información de ubicación de la madre
        $ubicacionMadre = [
            'potrero' => $vaca->potrero ? $vaca->potrero->nombre : 'No pertenece a ningun potrero',
            'lote' => $vaca->lote ? $vaca->lote->nombre : 'No pertenece a ningun lote',
        ];

        // Obtener todas las crías asociadas a la vaca usando el accesor 'crias'
        $crias = $vaca->crias;

        $criasFormatted = $crias->map(function ($cria) {
            return [
                'id_animal' => $cria->id_animal,
                'codigo' => $cria->codigo,
                'nombre' => $cria->nombre,
            ];
        });

        // Preparar los datos para la respuesta
        $data = [
            'fecha_ultimo_parto' => $fechaUltimoParto,
            'dias_prenez' => $tiempoPrenez,
            'total_leche' => $totalLeche,
            'fecha_ultimo_estado_reproductivo' => $fechaEstadoFormatted,
            'vaca_codigo' => $vaca->codigo,
            'vaca_nombre' => $vaca->nombre,
            'vaca_sexo' => ucfirst($vaca->sexo),
            'ubicacion_madre' => $ubicacionMadre,
            'crias' => $criasFormatted,
        ];

        // Retornar la respuesta en formato JSON
        return response()->json([
            'success' => true,
            'type' => 'vaca',
            'data' => $data,
        ]);
    }

    private function calcularTiempoLegible($dias)
    {
        if ($dias >= 30) {
            $meses = floor($dias / 30);
            $diasRestantes = $dias % 30;

            return "{$meses} mes" . ($meses > 1 ? 'es' : '') . " y {$diasRestantes} día" . ($diasRestantes > 1 ? 's' : '');
        }

        return "{$dias} día" . ($dias > 1 ? 's' : '');
    }


    public function getCriaDetails(Request $request)
    {
        $request->validate([
            'id_cria_animal' => 'required|exists:animales,id_animal',
        ]);

        $idCria = $request->input('id_cria_animal');
        $usuario = Auth::user();

        if ($usuario->role->name !== 'admin') {
            $predioIdsUsuario = $usuario->predios->pluck('id')->toArray();
            $cria = Animal::where('id_animal', $idCria)
                ->whereIn('id_predio', $predioIdsUsuario)
                ->with([
                    'predio',
                    'potrero',
                    'lote',
                    'ultimoParto',
                    'ultimoEstadoReproductivo.estadoReproductivo'
                ])
                ->first();
        } else {
            $cria = Animal::where('id_animal', $idCria)
                ->with([
                    'predio',
                    'potrero',
                    'lote',
                    'ultimoParto',
                    'ultimoEstadoReproductivo.estadoReproductivo'
                ])
                ->firstOrFail();
        }

        if (!$cria) {
            return response()->json([
                'success' => false,
                'message' => 'Cría no encontrada o no autorizada.'
            ], 403);
        }

        $ultimoEstadoReproductivo = $cria->ultimoEstadoReproductivo;

        if ($ultimoEstadoReproductivo && $ultimoEstadoReproductivo->estadoReproductivo->id == 1) {
            $fechaEstado = $ultimoEstadoReproductivo->fecha_inicio instanceof Carbon
                ? $ultimoEstadoReproductivo->fecha_inicio
                : Carbon::parse($ultimoEstadoReproductivo->fecha_inicio);

            $diasPrenez = now()->diffInDays($fechaEstado, false);
            $fechaEstadoFormatted = $fechaEstado->format('Y-m-d');
        } else {
            $diasPrenez = 'N/A';
            $fechaEstadoFormatted = 'N/A';
        }

        $totalLecheCria = $cria->pesajeLeches()->sum('total_pesaje') ?? 0;

        $ubicacionCria = [
            'potrero' => $cria->potrero ? $cria->potrero->nombre : 'No pertenece a ningun potrero',
            'lote' => $cria->lote ? $cria->lote->nombre : 'No pertenece a ningun lote',
        ];

        // Obtener el nombre de la madre si el campo 'madre' no es una relación
        $madreNombre = $cria->madre; // Ajustar según el campo en la base de datos

        $data = [
            'dias_prenez' => $diasPrenez,
            'total_leche_cria' => $totalLecheCria,
            'fecha_ultimo_estado_reproductivo' => $fechaEstadoFormatted,
            'cria_codigo' => $cria->codigo,
            'cria_nombre' => $cria->nombre,
            'cria_sexo' => ucfirst($cria->sexo),
            'ubicacion_cria' => $ubicacionCria,
            'madre_codigo' => $madreNombre ?? 'N/A',
        ];

        return response()->json([
            'success' => true,
            'type' => 'cria',
            'data' => $data
        ]);
    }

/* Metodo store */

public function store(Request $request)
{
    try {
        DB::beginTransaction();

        $request->validate([
            'id_animal'     => 'required|exists:animales,id_animal',
            'fecha_destete' => 'required|date',
            'motivo'        => 'required|in:normal,preñez,enfermedad,muerte,mala_madre,aborto',
            'peso_vaca'     => 'nullable|numeric',
            'peso_cria'     => 'nullable|numeric',
        ]);

        // Obtener la vaca madre
        $vaca = Animal::findOrFail($request->id_animal);

        // Procesar vaca secado: se marca la vaca como seca y se registra el cambio.
        if ($request->has('vaca_secado') && $request->vaca_secado) {
            $vaca->estado_productivo_id = EstadoProductivo::VACA_SECA;
            $vaca->save();

            AnimalEstadoProductivo::create([
                'id_animal'            => $vaca->id_animal,
                'id_estado_productivo' => EstadoProductivo::VACA_SECA,
                'fecha_inicio'         => $request->fecha_destete,
                'fecha_fin'            => null,
            ]);

            // Si se selecciona "allCrias", se procesa para todas las crias de la vaca.
            if ($request->id_cria_animal == 'allCrias') {
                $crias = $vaca->crias; // Relación que debe devolver todas las crias de la vaca
                foreach ($crias as $cria) {
                    SecadoDestete::create([
                        'id_animal'         => $vaca->id_animal,
                        'id_cria_animal'    => $cria->id_animal,
                        'is_cria_levante'   => false,
                        'vaca_secado'       => true,
                        'peso_cria'         => null,
                        'peso_vaca'         => $request->peso_vaca,
                        'fecha_destete'     => $request->fecha_destete,
                        'motivo'            => $request->motivo,
                        'observacion'       => $request->observacion ?? null,
                    ]);
                }
                DB::commit();
                return redirect()->route('secado_destetes.index')
                    ->with('success', 'La vaca se ha marcado como seca y el destete se registró para todas las crias.');
            }
        }

        // Procesar cría levante: si se marca is_cria_levante, se actualiza el estado de la cría o de todas si se selecciona "allCrias".
        if ($request->has('is_cria_levante') && $request->is_cria_levante) {
            if ($request->id_cria_animal == 'allCrias') {
                $crias = $vaca->crias;
                foreach ($crias as $cria) {
                    $estadoProductivoCria = ($cria->sexo === 'hembra')
                        ? EstadoProductivo::HEMBRA_LEVANTE
                        : EstadoProductivo::MACHO_LEVANTE;
                    $estadoReproductivoCria = ($cria->sexo === 'hembra')
                        ? EstadoReproductivo::VACIA
                        : null;

                    $cria->estado_productivo_id = $estadoProductivoCria;
                    $cria->estado_reproductivo_id = $estadoReproductivoCria;
                    $cria->save();

                    AnimalEstadoProductivo::create([
                        'id_animal'            => $cria->id_animal,
                        'id_estado_productivo' => $estadoProductivoCria,
                        'fecha_inicio'         => $request->fecha_destete,
                        'fecha_fin'            => null,
                    ]);

                    if ($estadoReproductivoCria) {
                        AnimalEstadoReproductivo::create([
                            'id_animal'              => $cria->id_animal,
                            'id_estado_reproductivo' => $estadoReproductivoCria,
                            'fecha_inicio'           => $request->fecha_destete,
                            'fecha_fin'              => null,
                        ]);
                    }

                    SecadoDestete::create([
                        'id_animal'         => $vaca->id_animal,
                        'id_cria_animal'    => $cria->id_animal,
                        'is_cria_levante'   => true,
                        'vaca_secado'       => false,
                        'peso_cria'         => $request->peso_cria,
                        'peso_vaca'         => $request->peso_vaca,
                        'fecha_destete'     => $request->fecha_destete,
                        'motivo'            => $request->motivo,
                        'observacion'       => $request->observacion ?? null,
                    ]);
                }
            } else {
                // Se procesa únicamente la cría seleccionada
                $cria = Animal::findOrFail($request->id_cria_animal);

                $estadoProductivoCria = ($cria->sexo === 'hembra')
                    ? EstadoProductivo::HEMBRA_LEVANTE
                    : EstadoProductivo::MACHO_LEVANTE;
                $estadoReproductivoCria = ($cria->sexo === 'hembra')
                    ? EstadoReproductivo::VACIA
                    : null;

                $cria->estado_productivo_id = $estadoProductivoCria;
                $cria->estado_reproductivo_id = $estadoReproductivoCria;
                $cria->save();

                AnimalEstadoProductivo::create([
                    'id_animal'            => $cria->id_animal,
                    'id_estado_productivo' => $estadoProductivoCria,
                    'fecha_inicio'         => $request->fecha_destete,
                    'fecha_fin'            => null,
                ]);

                if ($estadoReproductivoCria) {
                    AnimalEstadoReproductivo::create([
                        'id_animal'              => $cria->id_animal,
                        'id_estado_reproductivo' => $estadoReproductivoCria,
                        'fecha_inicio'           => $request->fecha_destete,
                        'fecha_fin'              => null,
                    ]);
                }

                SecadoDestete::create([
                    'id_animal'         => $vaca->id_animal,
                    'id_cria_animal'    => $cria->id_animal,
                    'is_cria_levante'   => true,
                    'vaca_secado'       => false,
                    'peso_cria'         => $request->peso_cria,
                    'peso_vaca'         => $request->peso_vaca,
                    'fecha_destete'     => $request->fecha_destete,
                    'motivo'            => $request->motivo,
                    'observacion'       => $request->observacion ?? null,
                ]);
            }
        }

        if ($request->id_cria_animal == 'allCrias') {
            $crias = $vaca->crias;
            foreach ($crias as $cria) {
                SecadoDestete::create([
                    'id_animal'         => $vaca->id_animal,
                    'id_cria_animal'    => $cria->id_animal,
                    'is_cria_levante'   => $request->is_cria_levante ?? false,
                    'vaca_secado'       => $request->vaca_secado ?? false,
                    'peso_cria'         => $request->peso_cria,
                    'peso_vaca'         => $request->peso_vaca,
                    'fecha_destete'     => $request->fecha_destete,
                    'motivo'            => $request->motivo,
                    'observacion'       => $request->observacion ?? null,
                ]);
            }
        } else {
            // Se procesa únicamente la cría seleccionada (si se especifica)
            SecadoDestete::create([
                'id_animal'         => $vaca->id_animal,
                'id_cria_animal'    => $request->id_cria_animal,
                'is_cria_levante'   => $request->is_cria_levante ?? false,
                'vaca_secado'       => $request->vaca_secado ?? false,
                'peso_cria'         => $request->peso_cria,
                'peso_vaca'         => $request->peso_vaca,
                'fecha_destete'     => $request->fecha_destete,
                'motivo'            => $request->motivo,
                'observacion'       => $request->observacion ?? null,
            ]);
        }

        DB::commit();
        return redirect()->route('secado_destetes.index')
            ->with('success', 'Secado y/o destete registrado exitosamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al registrar el secado y/o destete: ' . $e->getMessage());
        return back()->with('error', 'Ocurrió un error al registrar el secado y/o destete. Por favor, inténtelo de nuevo.')
            ->withInput();
    }
}







}
