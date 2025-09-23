<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\EstadoReproductivo;
use App\Models\EstadoProductivo;
use App\Models\AnimalEstadoReproductivo;
use App\Models\AnimalEstadoProductivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;




class AnimalEstadoController extends Controller
{
    public function create($animal_id)
    {
        // Obtener el animal por su ID
        $animal = Animal::findOrFail($animal_id);
        
        // Obtener los posibles estados reproductivos y productivos
        $estadosReproductivos = EstadoReproductivo::where('sexo', $animal->sexo)->get();
        $estadosProductivos = EstadoProductivo::all();  // Solo vacas tienen estados productivos
        
        return view('animal_estado.create', compact('animal', 'estadosReproductivos', 'estadosProductivos'));
    }

    public function getEstados($animal_id)
    {
        // Encontrar el animal por su ID
        $animal = Animal::findOrFail($animal_id);
    
        // Inicializar las variables para los estados
        $estadosReproductivos = [];
        $estadosProductivos = [];
    
        // Obtener los estados reproductivos solo si el sexo coincide (macho o hembra)
        if ($animal->sexo) {
            $estadosProductivos = EstadoProductivo::where('sexo', $animal->sexo)->get();
        }
    
        // Si el animal es hembra, obtener los estados productivos
        if ($animal->sexo == 'hembra') {
            $estadosReproductivos = EstadoReproductivo::all();
        }
    
        // Responder con los datos en formato JSON
        return response()->json([
            'estadosReproductivos' => $estadosReproductivos,
            'estadosProductivos' => $estadosProductivos
        ]);
    }
    

    public function store(Request $request)
    {
        DB::beginTransaction(); // Iniciar una transacción
    
        try {
            // Validación de los datos recibidos
            $validated = $request->validate([
                'id_animal' => 'required|exists:animales,id_animal',
                'estado_reproductivo_id' => 'required|exists:estado_reproductivo,id', 
                'estado_productivo_id' => 'nullable|exists:estado_productivo,id',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            ]);
    
            // Encontrar el animal con el id proporcionado
            $animal = Animal::findOrFail($validated['id_animal']);
    
            // Crear el estado reproductivo
            $animalEstadoReproductivo = new AnimalEstadoReproductivo();
            $animalEstadoReproductivo->id_animal = $validated['id_animal'];
            $animalEstadoReproductivo->id_estado_reproductivo = $validated['estado_reproductivo_id']; 
            $animalEstadoReproductivo->fecha_inicio = $validated['fecha_inicio'];
            $animalEstadoReproductivo->fecha_fin = $validated['fecha_fin'] ?? null;
            $animalEstadoReproductivo->save();
    
            // Si el animal es hembra y se proporciona un estado productivo, creamos el estado productivo
            if ($animal->sexo == 'hembra' && $validated['estado_productivo_id']) {
                $animalEstadoProductivo = new AnimalEstadoProductivo();
                $animalEstadoProductivo->id_animal = $validated['id_animal'];
                $animalEstadoProductivo->id_estado_productivo = $validated['estado_productivo_id'];
                $animalEstadoProductivo->fecha_inicio = $validated['fecha_inicio'];
                $animalEstadoProductivo->fecha_fin = $validated['fecha_fin'] ?? null;
                $animalEstadoProductivo->save();
            }
    
            // Confirmar la transacción
            DB::commit();
    
            // Redirigir con éxito
            return redirect()->route('inventario.index')->with('success', 'Estado actualizado correctamente');
    
        } catch (\Exception $e) {
            // Si ocurre un error, revertir la transacción
            DB::rollBack();
    
            // Registrar el error para depuración
            Log::error('Error al guardar el estado del animal: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
    
            // Redirigir con mensaje de error
            return redirect()->back()->with(['error' => 'Ocurrió un error al guardar el estado. Inténtalo de nuevo.'])->withInput();
        }
    }

    
}
