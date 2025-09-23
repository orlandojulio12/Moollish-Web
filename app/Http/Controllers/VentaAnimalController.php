<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\VentaAnimal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VentaAnimalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        try {
            if ($user->role->name === 'admin') {
                $animales = Animal::filtrarPorEstadoYPredio($user)->get(); // Usando scope para animales activos
                $ventas = VentaAnimal::with('animal')->get(); // Todas las ventas
            } else {
                $animales = Animal::filtrarPorEstadoYPredio($user)
                    ->whereIn('id_predio', $user->predios->pluck('id')->toArray())
                    ->get();
                $ventas = VentaAnimal::with('animal')
                    ->whereHas('animal', function ($query) use ($user) {
                        $query->whereIn('id_predio', $user->predios->pluck('id'));
                    })
                    ->get(); // Ventas de animales asociados a los predios del usuario
            }

            return view('inventario_animales.VentaAnimal', compact('animales', 'ventas'));
        } catch (\Exception $e) {
            Log::error('Error al obtener datos para las ventas de animales: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al cargar los datos de ventas de animales.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_animal' => 'required|exists:animales,id_animal',
            'fecha_venta' => 'required|date',
            'precio' => 'required|numeric|min:1',
            'comprador' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $venta = new VentaAnimal();
            $venta->id_animal = $request->id_animal;
            $venta->fecha_venta = $request->fecha_venta;
            $venta->precio = $request->precio;
            $venta->comprador = $request->comprador;
            $venta->observaciones = $request->observaciones;
            $venta->save();

            // Actualizar el estado del animal a vendido
            $animal = Animal::findOrFail($request->id_animal);
            $animal->estado_vida = 3; // Estado 3: Vendido
            $animal->save();

            Log::info('Venta registrada correctamente para el animal con ID: ' . $animal->id_animal);

            return redirect()->route('VentaAnimal.index')->with('success', 'La venta del animal se ha registrado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al registrar la venta de un animal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al registrar la venta del animal.');
        }
    }
}
