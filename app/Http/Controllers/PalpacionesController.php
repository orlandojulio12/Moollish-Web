<?php

namespace App\Http\Controllers;

use App\Models\AnimalEstadoReproductivo;
use App\Models\EstadoReproductivo;
use App\Models\Animal;
use App\Models\Predios;
use App\Models\EstadoProductivo;
use App\Models\Palpacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Veterinario;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PalpacionesImport;
use App\Exports\PalpacionesTemplateExport;
use Illuminate\Support\Facades\Log;

class PalpacionesController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();

        $animales = Animal::filtrarPorEstadoYPredio($user)
            ->where('sexo', 'hembra') // Filtrar por sexo
            ->whereNotIn('estado_productivo_id', [
                EstadoProductivo::CRIA_HEMBRA,
                EstadoProductivo::HEMBRA_LEVANTE
            ]) // Excluir ciertos estados productivos
            ->get();

        if ($user->role->name === 'admin') {
            $predios = Predios::all();
            $veterinarios = Veterinario::all();
        } else {
            $predios = $user->predios;
            $veterinarios = Veterinario::whereIn('predio_id', $predios->pluck('id'))->get();
        }
        $animalesIds = $animales->pluck('id_animal');
        $palpaciones = Palpacion::whereIn('id_animal', $animalesIds)->get();
        /* $palpaciones = Palpacion::all(); */

        // Pasar las variables a la vista
        return view('inventario_animales.Palpaciones', compact('animales', 'veterinarios', 'predios', 'palpaciones'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'fecha'           => 'required|date',
            'id_animal'       => 'required|exists:animales,id_animal',
            'resultado'       => 'required|in:Prenada,Vacia',
            'parto_proyectado'=> 'nullable|date',
            'id_palpador'     => 'required|exists:veterinarios,id',
            // Si el resultado es Vacia, diagnostico es requerido y debe ser uno de los valores indicados.
            'diagnostico'      => 'required_if:resultado,Vacia|in:Vacía ciclando,Vacía estática,Vacia normal,Cuerpo Luteo ovario derecho,Cuerpo Luteo ovario izquierdo,Folículo ovario derecho,Folículo ovario izquierdo,Quistes,indantilismo genital',
        ]);

        try {
            DB::beginTransaction();

            // Crear la nueva palpación (incluyendo el campo diagnostico)
            $palpacion = Palpacion::create([
                'fecha'           => $request->fecha,
                'id_animal'       => $request->id_animal,
                'resultado'       => $request->resultado,
                'parto_proyectado'=> $request->parto_proyectado,
                'id_palpador'     => $request->id_palpador,
                'diagnostico'     => $request->resultado === 'Vacia' ? $request->diagnostico : null,
            ]);


            // Obtener el animal asociado
            $animal = Animal::findOrFail($request->id_animal);

            // Determinar el nuevo estado reproductivo según el resultado
            $nuevoEstadoReproductivo = $request->resultado === 'Prenada'
                ? EstadoReproductivo::PRENADA
                : EstadoReproductivo::VACIA;

            // Actualizar el estado reproductivo del animal
            $animal->estado_reproductivo_id = $nuevoEstadoReproductivo;
            $animal->save();

            // Registrar el nuevo estado en la tabla animal_estado_reproductivo
            AnimalEstadoReproductivo::create([
                'id_animal'              => $animal->id_animal,
                'id_estado_reproductivo' => $nuevoEstadoReproductivo,
                'fecha_inicio'           => $request->fecha,
                'fecha_fin'              => null,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Palpación registrada correctamente y estado reproductivo actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar la palpación: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al registrar la palpación. Por favor, inténtelo de nuevo.');
        }
    }



    public function PorPalpar()
    {
        $user = Auth::user();

        // Obtener los predios del usuario
        $predios = $user->predios()->pluck('id_predio');

        // Filtrar hembras con más de 24 meses de edad
        $hembras = Animal::where('sexo', 'hembra')
            ->whereRaw('TIMESTAMPDIFF(MONTH, fecha_nacimiento, CURDATE()) > 24')
            ->whereIn('estado_productivo_id', [
                EstadoProductivo::VACA_PARIDA,
                EstadoProductivo::VACA_SECA,
                EstadoProductivo::NOVILLA_VIENTRE
            ])
            ->filtrarPorEstadoYPredio($user)
            ->with(['ultimoTacto', 'estadoProductivo', 'estadoReproductivo'])
            ->get();

        // Obtener los veterinarios asociados a los predios del usuario
        $veterinarios = Veterinario::whereIn('predio_id', $predios)->get();

        return view('reportes.ProximasPalpaciones', compact('hembras', 'veterinarios'));
    }

public function downloadTemplate()
{
    return Excel::download(new PalpacionesTemplateExport(), 'plantilla_palpaciones.xlsx');
}

public function import(Request $request)
{
    $request->validate([
        'predio_id' => 'required|exists:predios,id',
        'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
    ]);

    try {
        Log::info('Inicio importación palpaciones');
        $file = $request->file('file');
        $predio_id = $request->predio_id;

        $import = new PalpacionesImport($predio_id);
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
                'message' => 'No se pudo importar ninguna palpación nueva',
                'errores' => $errores,
                'duplicados' => $duplicados,
                'exitosos' => 0
            ], 422);
        }

        if ($exitosos > 0 && ($totalErrores > 0 || $totalDuplicados > 0)) {
            return response()->json([
                'status' => 'partial',
                'message' => "Se importaron {$exitosos} palpación(es) correctamente",
                'exitosos' => $exitosos,
                'duplicados' => $duplicados,
                'errores' => $errores
            ], 207);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Se importaron {$exitosos} palpación(es) exitosamente",
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
