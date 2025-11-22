<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MontaNatural;
use App\Models\Animal;
use App\Models\EstadoProductivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MontaTemplateExport;
use App\Imports\MontaImport;

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

 public function downloadTemplate(Request $request)
    {
        $request->validate([
            'predio_id' => 'required|exists:predios,id',
        ]);

        $predio_id = $request->predio_id;
        
        return Excel::download(
            new MontaTemplateExport($predio_id), 
            'plantilla_monta_natural.xlsx'
        );
    }

    /**
     * Importar montas naturales desde Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'predio_id' => 'required|exists:predios,id',
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Log::info('Inicio importación monta natural');
            $file = $request->file('file');
            $predio_id = $request->predio_id;

            $import = new MontaImport($predio_id);
            Excel::import($import, $file);

            $exitosos = $import->getExitosos();
            $duplicados = $import->getDuplicados();
            $errores = $import->getErrores();
            $totalDuplicados = count($duplicados);
            $totalErrores = count($errores);

            Log::info("Importación completada: {$exitosos} exitosos, {$totalDuplicados} duplicados, {$totalErrores} errores");

            if ($exitosos == 0) {
                if ($totalErrores == 0 && $totalDuplicados == 0) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'El archivo no contiene registros válidos para importar',
                        'errores' => ['El archivo está vacío o no tiene datos válidos'],
                        'duplicados' => [],
                        'exitosos' => 0
                    ], 422);
                }
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se pudo importar ninguna monta natural',
                    'errores' => $errores,
                    'duplicados' => $duplicados,
                    'exitosos' => 0
                ], 422);
            }

            if ($exitosos > 0 && ($totalErrores > 0 || $totalDuplicados > 0)) {
                return response()->json([
                    'status' => 'partial',
                    'message' => "Se importaron {$exitosos} monta(s) natural(es) correctamente",
                    'exitosos' => $exitosos,
                    'duplicados' => $duplicados,
                    'errores' => $errores
                ], 207);
            }

            return response()->json([
                'status' => 'success',
                'message' => "Se importaron {$exitosos} monta(s) natural(es) exitosamente",
                'exitosos' => $exitosos,
                'duplicados' => [],
                'errores' => []
            ]);

        } catch (\Exception $e) {
            Log::error('Error crítico en import: ' . $e->getMessage());
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
