<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MontaNatural;
use App\Models\Animal;
use App\Models\EstadoProductivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MontaNaturalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $predios = $user->predios->pluck('id');

        // Estados productivos para vacas y toros
        $estadosProductivos = [
            EstadoProductivo::HEMBRA_LEVANTE,
            EstadoProductivo::NOVILLA_VIENTRE,
            EstadoProductivo::VACA_SECA,
            EstadoProductivo::VACA_PARIDA,
        ];
        $estadosProductivosMachos = [
            EstadoProductivo::MACHO_LEVANTE,
            EstadoProductivo::MACHO_CEBA,
            EstadoProductivo::REPRODUCTOR_TORO,
        ];

        // Obtener vacas y toros según los estados productivos y predios del usuario
        $vacas = Animal::filtrarPorEstadoYPredio($user, $estadosProductivos)->get();
        $toros = Animal::filtrarPorEstadoYPredio($user, $estadosProductivosMachos)->get();

        // Filtrar las montas naturales relacionadas con las vacas y toros obtenidos
        $vacaIds = $vacas->pluck('id_animal');
        $toroIds = $toros->pluck('id_animal');
        $montas = MontaNatural::with(['vaca', 'toro'])
        ->where(function ($query) use ($vacaIds, $toroIds) {
            $query->whereIn('id_vaca', $vacaIds)
                  ->orWhereIn('id_toro', $toroIds);
        })
        ->get();


        return view('inventario_animales.MontaNatural', compact('vacas', 'toros', 'montas'));
    }


    public function store(Request $request)
{
    try {
        // Validar los datos de entrada
        $request->validate([
            'id_vaca' => [
                'required',
                'exists:animales,id_animal',
                function ($attribute, $value, $fail) {
                    $animal = Animal::find($value);
                    if ($animal && !in_array($animal->estado_productivo_id, [
                        EstadoProductivo::HEMBRA_LEVANTE,
                        EstadoProductivo::NOVILLA_VIENTRE,
                        EstadoProductivo::VACA_SECA,
                        EstadoProductivo::VACA_PARIDA,
                    ])) {
                        $fail('La vaca seleccionada no está en un estado válido para la monta natural.');
                    }
                },
            ],
            'id_toro' => [
                'required',
                'exists:animales,id_animal',
                function ($attribute, $value, $fail) {
                    $animal = Animal::find($value);
                    if ($animal && !in_array($animal->estado_productivo_id, [
                        EstadoProductivo::MACHO_LEVANTE,
                        EstadoProductivo::MACHO_CEBA,
                        EstadoProductivo::REPRODUCTOR_TORO,
                    ])) {
                        $fail('El toro seleccionado no está en un estado válido para la monta natural.');
                    }
                },
            ],
            'fecha_monta' => 'required|date|before_or_equal:today',
        ]);

        // Registrar la monta natural
        $monta = MontaNatural::create([
            'id_vaca' => $request->id_vaca,
            'id_toro' => $request->id_toro,
            'fecha_monta' => $request->fecha_monta,
        ]);

        return redirect()
            ->route('monta_natural.index')
            ->with('success', 'La monta natural se registró exitosamente.');
    } catch (\Exception $e) {
        // Manejo de errores
        Log::error('Error al registrar monta natural: ' . $e->getMessage());

        return redirect()
            ->route('monta_natural.index')
            ->with('error', 'Ocurrió un error al registrar la monta natural. Por favor, inténtelo de nuevo.');
    }
}


}
