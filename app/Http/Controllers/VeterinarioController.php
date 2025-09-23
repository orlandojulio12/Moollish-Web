<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Veterinario;
use App\Models\Predios;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VeterinarioController extends Controller
{
    //
    public function index() {
        $user = Auth::user();
        $predios = $user->role->name === 'admin'
            ? Predios::all() // Admin: Todos los predios
            : $user->predios; // Usuario: Predios asociados
    
        // Obtener los IDs de los predios
        $predioIds = $predios->pluck('id');
    
        // Obtener veterinarios filtrados por predios
        $veterinarios = Veterinario::whereIn('predio_id', $predioIds)
            ->orderBy('nombre_completo', 'asc')
            ->get();
    
        return view('inventario_animales.Veterinarios', compact('veterinarios', 'predios'));
    }
    public function getVeterinarios($userId)
    {
        try {
            // Obtener el usuario por ID
            $user = User::find($userId);
    
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.',
                ], 404);
            }
    
            // Obtener los predios asociados al usuario si no es admin
            $predios = $user->role->name === 'admin'
                ? Predios::all()->pluck('id') // Admin: Todos los predios
                : $user->predios->pluck('id'); // Usuario: Predios asociados
    
            // Obtener veterinarios filtrados por predios
            $veterinarios = Veterinario::whereIn('predio_id', $predios)
                ->orderBy('nombre_completo', 'asc')
                ->get();
    
            return response()->json([
                'success' => true,
                'veterinarios' => $veterinarios,
            ], 200);
    
        } catch (\Exception $e) {
            \Log::error('Error al obtener veterinarios: ' . $e->getMessage(), ['exception' => $e]);
    
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado al obtener los veterinarios.',
            ], 500);
        }
    }
    



    public function store(Request $request)
{
    $request->validate([
        'predio_id' => 'required|string|max:255',
        'nombre_completo' => 'required|string|max:255',
        'numero_documento' => 'nullable|string',
        'celular' => 'nullable|string|max:15',
        'correo_electronico' => 'nullable|email|max:255', // Corrección aquí
        'sexo' => 'nullable|string',
    ]);

    try {
        $veterinario = Veterinario::create([
            'predio_id' => $request->predio_id,
            'nombre_completo' => $request->nombre_completo,
            'numero_documento' => $request->numero_documento,
            'celular' => $request->celular,
            'correo_electronico' => $request->correo_electronico, // Corrección en el nombre del campo
            'sexo' => $request->sexo,
        ]);

        return response()->json([
            'success' => true,
            'veterinario' => $veterinario,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error (backend) al agregar el veterinario.',
        ], 500);
    }
}

    
}
