<?php

namespace App\Http\Controllers;

use App\Models\IdentificacionAnimal;
use App\Models\Predios;
use App\Models\CensoBufalino;
use App\Models\CensoBovino;
use App\Models\CensoPorcino;
use Illuminate\Http\Request;

class IdentificacionAnimalController extends Controller
{
    // Función para almacenar nueva identificación animal
    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'id_predio' => 'nullable|exists:predios,id',
                'porcinos_con' => 'nullable|integer',
                'porcinos_sin' => 'nullable|integer',
                'total_porcinos' => 'nullable|integer',
                'bovinos_con' => 'nullable|integer',
                'bovinos_sin' => 'nullable|integer',
                'total_bovinos' => 'nullable|integer',
                'bufalinos_con' => 'nullable|integer',
                'bufalinos_sin' => 'nullable|integer',
                'total_bufalinos' => 'nullable|integer',
            ]);
    
            // Crear el registro en la base de datos
            $identificacionAnimal = IdentificacionAnimal::create($validatedData);
    
            // Redirigir con un mensaje de éxito
            return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
                             ->with('success', 'Identificación animal creada exitosamente.');
    
        } catch (\Exception $e) {
            // Redirigir con un mensaje de error si ocurre una excepción
            return redirect()->back()->withErrors(['error' => 'Error al crear la identificación animal: ' . $e->getMessage()]);
        }
    }
    

    // Función para mostrar una identificación animal específica
    public function show($id)
    {
        $predioId = Predios::findOrFail($id)->id;
    
        // Traer los totales de las tablas correspondientes
        $totalBufalinos = CensoBufalino::where('id_predio', $predioId)->sum('total_bufalinos');
        $totalBovinos = CensoBovino::where('id_predio', $predioId)->sum('total_bovinos');
        $totalPorcinos = CensoPorcino::where('id_predio', $predioId)->sum('total_porcinos');
    
        // Verificar si existe un registro en IdentificacionAnimal
        $IdentificacionAnimal = IdentificacionAnimal::where('id_predio', $predioId)->exists();
        $identificacionAnimal = $IdentificacionAnimal ? IdentificacionAnimal::where('id_predio', $predioId)->first() : new IdentificacionAnimal;

    
        // Pasar los totales a la vista
        return view('identificacion_animal.show', compact('predioId', 'IdentificacionAnimal', 'totalBufalinos', 'totalBovinos', 'totalPorcinos', 'identificacionAnimal'));
    }
    


    // Función para actualizar una identificación animal existente
    public function update(Request $request, $id)
    {
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'id_predio' => 'nullable|exists:predios,id',
                'porcinos_con' => 'nullable|integer',
                'porcinos_sin' => 'nullable|integer',
                'total_porcinos' => 'nullable|integer',
                'bovinos_con' => 'nullable|integer',
                'bovinos_sin' => 'nullable|integer',
                'total_bovinos' => 'nullable|integer',
                'bufalinos_con' => 'nullable|integer',
                'bufalinos_sin' => 'nullable|integer',
                'total_bufalinos' => 'nullable|integer',
            ]);
    
            // Buscar el registro correspondiente en la base de datos
            $identificacionAnimal = IdentificacionAnimal::findOrFail($id);
            
            // Actualizar el registro
            $identificacionAnimal->update($validatedData);
    
            // Redirigir con un mensaje de éxito
            return redirect()->route('Seccion6', ['id' => $validatedData['id_predio']])
                             ->with('success', 'Identificación animal actualizada exitosamente.');
    
        } catch (\Exception $e) {
            // Redirigir con un mensaje de error si ocurre una excepción
            return redirect()->back()->withErrors(['error' => 'Error al actualizar la identificación animal: ' . $e->getMessage()]);
        }
    }
    
}



