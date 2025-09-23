<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\MuerteAnimal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MuerteAnimalController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $predios = $user->predios;
            // Obtener animales vivos según el rol del usuario
            if ($user->role->name === 'admin') {
                $animales = Animal::where('estado_vida', 1)->get();
                $muertes = MuerteAnimal::with('animal')->get(); // Obtener todas las muertes con información del animal
            } else {
                $animales = Animal::where('estado_vida', 1)
                    ->whereIn('id_predio', $user->predios->pluck('id')->toArray())
                    ->get();

                // Obtener solo las muertes relacionadas con los predios del usuario
                $muertes = MuerteAnimal::whereHas('animal', function ($query) use ($user) {
                    $query->whereIn('id_predio', $user->predios->pluck('id')->toArray());
                })->with('animal')->get();
            }

            // Retornar vista con animales y muertes
            return view('inventario_animales.MuerteAnimal', compact('predios','animales', 'muertes'));

        } catch (\Exception $e) {
            // Registrar el error en el log
            Log::error('Error al cargar el índice de muertes: ' . $e->getMessage());

            // Redirigir con mensaje de error
            return redirect()->back()->with('error', 'No se pudo cargar la información. Intente nuevamente.');
        }
    }


    public function store(Request $request)
    {
        try {
            // Validar los datos del formulario
            $validated = $request->validate([
                'id_animal' => 'required|exists:animales,id_animal',
                'fecha_muerte' => 'required|date',
                'observaciones' => 'nullable|string|max:255',
            ]);

            // Registrar la muerte del animal
            MuerteAnimal::create($validated);

            // Cambiar el estado del animal a 2 (muerto)
            $animal = Animal::findOrFail($validated['id_animal']);
            $animal->update(['estado_vida' => 2]);

            // Registrar el éxito en el log
            Log::info('Muerte registrada con éxito para el animal ID: ' . $animal->id_animal);

            // Redirigir con mensaje de éxito
            return redirect()->route('MuerteAnimal.index')->with('success', 'Muerte registrada exitosamente.');
        } catch (\Exception $e) {
            // Registrar el error en el log
            Log::error('Error al registrar la muerte de un animal: ' . $e->getMessage());

            // Redirigir con mensaje de error
            return redirect()->back()->with('error', 'No se pudo registrar la muerte del animal. Intente nuevamente.');
        }
    }

}
