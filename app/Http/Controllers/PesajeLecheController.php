<?php

namespace App\Http\Controllers;

use App\Models\PesajeLeche;
use App\Models\Animal;
use App\Models\EstadoProductivo;
use App\Models\Predios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PesajeLecheController extends Controller
{
    public function index()
{
    $user = Auth::user();

    if ($user->role->name === 'admin') {
        $pesajes = PesajeLeche::with('animal')->get();
    } else {
        $pesajes = PesajeLeche::with('animal')
            ->where('created_by', $user->id)
            ->get();
    }

    if ($user->role->name === 'admin') {
        $prediosIds = Predios::pluck('id'); // Todos los predios
    } else {
        $prediosIds = $user->predios()->pluck('id_predio');
    }

    $animales_store = Animal::whereIn('id_predio', $prediosIds)
        ->where('sexo', 'hembra')
        ->whereHas('estadosProductivos', function ($query) {
            $query->where('id_estado_productivo', EstadoProductivo::VACA_PARIDA)
                ->where(function ($q) {
                    $q->whereNull('fecha_fin')
                        ->orWhere('fecha_fin', '>=', now());
                });
        })
        ->get();

    foreach ($animales_store as $animal) {
        $ultimoParto = $animal->ultimoParto; // Equivalente a: $animal->ultimoParto()->first();

        if ($ultimoParto && $ultimoParto->fecha_parto) {
            $fechaParto = $ultimoParto->fecha_parto instanceof Carbon
                ? $ultimoParto->fecha_parto
                : Carbon::parse($ultimoParto->fecha_parto);

            $diff = $fechaParto->diff(now());
            $componentes = [];

            if ($diff->y > 0) {
                $componentes[] = $diff->y . ' año' . ($diff->y > 1 ? 's' : '');
            }
            if ($diff->m > 0 && count($componentes) < 2) {
                $componentes[] = $diff->m . ' mes' . ($diff->m > 1 ? 'es' : '');
            }
            if ($diff->d > 0 && count($componentes) < 2) {
                $componentes[] = $diff->d . ' día' . ($diff->d > 1 ? 's' : '');
            }
            if ($diff->h > 0 && count($componentes) < 2) {
                $componentes[] = $diff->h . ' hora' . ($diff->h > 1 ? 's' : '');
            }
            if ($diff->i > 0 && count($componentes) < 2) {
                $componentes[] = $diff->i . ' minuto' . ($diff->i > 1 ? 's' : '');
            }
            if (empty($componentes)) {
                $componentes[] = 'menos de un minuto';
            }

            $diasParidaString = implode(' y ', array_slice($componentes, 0, 2));

            // Asignar la cadena legible
            $animal->dias_parida = $diasParidaString;

            // Calcular el número total de días usando la relación correcta
            $diasParidaNumero = floor(Carbon::parse($ultimoParto->fecha_parto)->diffInDays(now()));
            $animal->dias_parida_numero = $diasParidaNumero;
        } else {
            $animal->dias_parida = 'Sin registros';
            $animal->dias_parida_numero = null;
        }
    }

    // Dump and die para verificar los valores calculados
   /*  dd($animales_store); */

    // El resto de tu lógica para la vista...
    $animales_historial = Animal::whereIn('id_predio', $prediosIds)
        ->where('sexo', 'hembra')
        ->whereHas('estadosProductivos', function ($query) {
            $query->where('id_estado_productivo', EstadoProductivo::VACA_PARIDA)
                ->where(function ($q) {
                    $q->whereNull('fecha_fin')
                        ->orWhere('fecha_fin', '>=', now());
                });
        })
        ->get();

    $pesaje = new PesajeLeche();
    $predios = Predios::all();
    return view('inventario_animales.PesajeLeche', compact('predios', 'pesajes', 'animales_store', 'animales_historial', 'pesaje'));
}


    public function historial(Request $request)
    {
        $user = Auth::user();

        // Validar los datos del formulario
        $request->validate([
            'id_animal' => 'required|exists:animales,id_animal',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        // Obtener los datos del formulario
        $animalId = $request->input('id_animal');
        $fechaInicio = Carbon::parse($request->input('fecha_inicio'))->startOfDay();
        $fechaFin = Carbon::parse($request->input('fecha_fin'))->endOfDay();

        // Verificar que el animal pertenece al usuario (si no es admin)
        if ($user->role->name !== 'admin') {
            $prediosIds = $user->predios()->pluck('id_predio');

            $animal = Animal::where('id_animal', $animalId)
                ->whereIn('id_predio', $prediosIds)
                ->first();

            if (!$animal) {
                return redirect()->back()->withErrors(['id_animal' => 'El animal seleccionado no está disponible para usted.'])->withInput();
            }
        }

        // Consultar los registros de pesaje de leche
        $historialPesajes = PesajeLeche::where('id_animal', $animalId)
            ->whereBetween('fecha_pesaje', [$fechaInicio, $fechaFin])
            ->orderBy('fecha_pesaje', 'desc')
            ->get();

        // Obtener los animales hembras en estado "VACA_PARIDA" para el formulario de registro
        if ($user->role->name === 'admin') {
            $prediosIds = Predios::pluck('id'); // Todos los predios
        } else {
            $prediosIds = $user->predios()->pluck('id_predio');
        }

        $animales_store = Animal::whereIn('id_predio', $prediosIds)
            ->where('sexo', 'hembra')
            ->whereHas('estadosProductivos', function ($query) {
                $query->where('id_estado_productivo', EstadoProductivo::VACA_PARIDA)
                    ->where(function ($q) {
                        $q->whereNull('fecha_fin')
                          ->orWhere('fecha_fin', '>=', now());
                    });
            })
            ->get();

        // Calcular "dias_parida" y "dias_parida_numero" para cada animal en el formulario de registro
        foreach ($animales_store as $animal) {
            $ultimoParto = $animal->partos()->latest('fecha_parto')->first();

            if ($ultimoParto && $ultimoParto->fecha_parto) {
                $fechaParto = $ultimoParto->fecha_parto instanceof Carbon
                    ? $ultimoParto->fecha_parto
                    : Carbon::parse($ultimoParto->fecha_parto);

                $diff = $fechaParto->diff(now());

                $componentes = [];

                if ($diff->y > 0) {
                    $componentes[] = $diff->y . ' año' . ($diff->y > 1 ? 's' : '');
                }
                if ($diff->m > 0 && count($componentes) < 2) {
                    $componentes[] = $diff->m . ' mes' . ($diff->m > 1 ? 'es' : '');
                }
                if ($diff->d > 0 && count($componentes) < 2) {
                    $componentes[] = $diff->d . ' día' . ($diff->d > 1 ? 's' : '');
                }
                if ($diff->h > 0 && count($componentes) < 2) {
                    $componentes[] = $diff->h . ' hora' . ($diff->h > 1 ? 's' : '');
                }
                if ($diff->i > 0 && count($componentes) < 2) {
                    $componentes[] = $diff->i . ' minuto' . ($diff->i > 1 ? 's' : '');
                }
                if (empty($componentes)) {
                    $componentes[] = 'menos de un minuto';
                }

                $diasParidaString = implode(' y ', array_slice($componentes, 0, 2));

                // Asignar la cadena legible
                $animal->dias_parida = $diasParidaString;

                // Calcular el número total de días
                $diasParidaNumero = $fechaParto->diffInDays(now());
                $animal->dias_parida_numero = $diasParidaNumero;
            } else {
                $animal->dias_parida = 'Sin registros';
                $animal->dias_parida_numero = null;
            }
        }

        // Obtener los animales hembras en estado "VACA_PARIDA" para el formulario de historial
        $animales_historial = Animal::whereIn('id_predio', $prediosIds)
            ->where('sexo', 'hembra')
            ->whereHas('estadosProductivos', function ($query) {
                $query->where('id_estado_productivo', EstadoProductivo::VACA_PARIDA)
                    ->where(function ($q) {
                        $q->whereNull('fecha_fin')
                          ->orWhere('fecha_fin', '>=', now());
                    });
            })
            ->get();
$predios = Predios::all();
        // **Eliminar** la referencia a 'pesajes'
        return view('inventario_animales.PesajeLeche', [
            // 'pesajes' => $pesajes, // **Eliminar esta línea**
            'animales_store' => $animales_store,
            'predios' => $predios,
            'animales_historial' => $animales_historial,
            'historialPesajes' => $historialPesajes,
            'pesaje' => new PesajeLeche(),
        ]);
    }


    // Método para obtener los animales disponibles (puedes ajustar según tu lógica actual)
    private function obtenerAnimalesDisponibles($user)
    {
        if ($user->role->name === 'admin') {
            $prediosIds = Predios::pluck('id');
        } else {
            $prediosIds = $user->predios()->pluck('id_predio');
        }

        $animales = Animal::whereIn('id_predio', $prediosIds)
            ->where('sexo', 'hembra')
            ->whereHas('estadosProductivos', function ($query) {
                $query->where('id_estado_productivo', EstadoProductivo::VACA_PARIDA)
                    ->where(function ($q) {
                        $q->whereNull('fecha_fin')
                            ->orWhere('fecha_fin', '>=', now());
                    });
            })
            ->get();

        // Calcular dias_parida y dias_parida_numero
        foreach ($animales as $animal) {
            $ultimoParto = $animal->partos()->latest('fecha_parto')->first();

            if ($ultimoParto && $ultimoParto->fecha_parto) {
                $fechaParto = $ultimoParto->fecha_parto instanceof Carbon
                    ? $ultimoParto->fecha_parto
                    : Carbon::parse($ultimoParto->fecha_parto);

                $diff = $fechaParto->diff(now());

                $componentes = [];

                if ($diff->y > 0) {
                    $componentes[] = $diff->y . ' año' . ($diff->y > 1 ? 's' : '');
                }
                if ($diff->m > 0 && count($componentes) < 2) {
                    $componentes[] = $diff->m . ' mes' . ($diff->m > 1 ? 'es' : '');
                }
                if ($diff->d > 0 && count($componentes) < 2) {
                    $componentes[] = $diff->d . ' día' . ($diff->d > 1 ? 's' : '');
                }
                if ($diff->h > 0 && count($componentes) < 2) {
                    $componentes[] = $diff->h . ' hora' . ($diff->h > 1 ? 's' : '');
                }
                if ($diff->i > 0 && count($componentes) < 2) {
                    $componentes[] = $diff->i . ' minuto' . ($diff->i > 1 ? 's' : '');
                }
                if (empty($componentes)) {
                    $componentes[] = 'menos de un minuto';
                }

                $diasParidaString = implode(' y ', array_slice($componentes, 0, 2));

                // Asignar la cadena legible
                $animal->dias_parida = $diasParidaString;

                // Calcular el número total de días
                $diasParidaNumero = $fechaParto->diffInDays(now());
                $animal->dias_parida_numero = $diasParidaNumero;
            } else {
                $animal->dias_parida = 'Sin registros';
                $animal->dias_parida_numero = null;
            }
        }

        return $animales;
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        try {
            $request->validate([
                'id_animal' => 'required|exists:animales,id_animal',
                'fecha_pesaje' => 'required|date_format:Y-m-d\TH:i',
                'pesaje_am' => 'nullable|numeric|min:0',
                'pesaje_pm' => 'nullable|numeric|min:0',
                'total_pesaje' => 'nullable|numeric|min:0',
                'dias_parida' => 'nullable|integer|min:0',
            ]);

            // Verificar que el animal pertenece al usuario (si no es admin)
            if ($user->role->name !== 'admin') {
                $prediosIds = $user->predios()->pluck('id_predio');

                $animal = Animal::where('id_animal', $request->id_animal)
                    ->whereIn('id_predio', $prediosIds)
                    ->first();

                if (!$animal) {
                    return redirect()->back()->withErrors(['id_animal' => 'El animal seleccionado no está disponible para usted.'])->withInput();
                }
            }

            $pesajeAm = $request->input('pesaje_am', 0);
            $pesajePm = $request->input('pesaje_pm', 0);
            $totalPesaje = $request->input('total_pesaje');

            if (is_null($totalPesaje) || $totalPesaje == 0) {
                $totalPesaje = $pesajeAm + $pesajePm;
            }

            $data = [
                'id_animal' => $request->id_animal,
                'fecha_pesaje' => Carbon::createFromFormat('Y-m-d\TH:i', $request->fecha_pesaje),
                'dias_parida' => $request->input('dias_parida', null),
                'pesaje_am' => $pesajeAm,
                'pesaje_pm' => $pesajePm,
                'total_pesaje' => $totalPesaje,
                'created_by' => $user->id,
            ];

            // Crear el registro en la base de datos
            PesajeLeche::create($data);

            return redirect()->route('pesajeLeche.index')
                ->with('success', 'Registro de pesaje de leche creado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear el registro de pesaje de leche: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al crear el registro de pesaje de leche. Por favor, inténtelo de nuevo. Detalles del error: ' . $e->getMessage())
                ->withInput();
        }
    }
}
