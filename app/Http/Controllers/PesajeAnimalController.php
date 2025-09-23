<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PesajeAnimal;
use App\Models\Animal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class PesajeAnimalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $animales = Animal::filtrarPorEstadoYPredio($user, [])->get();
        return view('animales.registro-peso', compact('animales'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_pesaje' => 'required|date',
            'id_animal_pesaje' => 'required|exists:animales,id_animal',
            'peso' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validación fallida',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pesaje = new PesajeAnimal();
            $pesaje->fecha_pesaje = $request->fecha_pesaje;
            $pesaje->id_animal = $request->id_animal_pesaje;
            $pesaje->peso = $request->peso;
            $pesaje->save();

            return response()->json([
                'success' => true,
                'message' => 'Peso registrado exitosamente',
                'id' => $pesaje->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Hubo un error al registrar el peso. Intente nuevamente',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
