<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Peso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PesoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesos = Peso::with('animal')->orderBy('fecha_peso', 'desc')->paginate(10);
        return view('pesos.index', compact('pesos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $animal = null;
        $animales = Animal::all();

        // Si se proporciona un ID de animal, preseleccionarlo
        if ($request->has('animal_id')) {
            $animal = Animal::find($request->animal_id);
        }

        return view('animales.registro-peso', compact('animales', 'animal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'animal_id' => 'required|exists:animales,id',
                'fecha_peso' => 'required|date',
                'peso' => 'required|numeric|min:0',
                'metodo_pesaje' => 'nullable|string',
                'observaciones' => 'nullable|string|max:500',
            ]);

            $peso = Peso::create($request->all());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peso registrado correctamente',
                    'id' => $peso->id
                ]);
            }

            return redirect()->route('pesos.index')
                ->with('success', 'Peso registrado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar el peso: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar el peso: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al registrar el peso: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $peso = Peso::with('animal')->findOrFail($id);
        return view('pesos.show', compact('peso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $peso = Peso::findOrFail($id);
        $animales = Animal::all();
        return view('pesos.edit', compact('peso', 'animales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'animal_id' => 'required|exists:animales,id',
                'fecha_peso' => 'required|date',
                'peso' => 'required|numeric|min:0',
                'metodo_pesaje' => 'nullable|string',
                'observaciones' => 'nullable|string|max:500',
            ]);

            $peso = Peso::findOrFail($id);
            $peso->update($request->all());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peso actualizado correctamente'
                ]);
            }

            return redirect()->route('pesos.index')
                ->with('success', 'Peso actualizado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar el peso: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el peso: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar el peso: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $peso = Peso::findOrFail($id);
            $peso->delete();

            return response()->json([
                'success' => true,
                'message' => 'Peso eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar el peso: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el peso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener los pesos de un animal específico.
     */
    public function getPesosAnimal(string $animalId)
    {
        try {
            $pesos = Peso::where('animal_id', $animalId)
                          ->orderBy('fecha_peso', 'desc')
                          ->get();

            return response()->json([
                'success' => true,
                'data' => $pesos
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener pesos del animal: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los pesos: ' . $e->getMessage()
            ], 500);
        }
    }
}
