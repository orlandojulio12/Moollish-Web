<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\User;
use App\Models\Predios;
use App\Models\Lote;
use App\Models\PartoAnimal;
use App\Models\Potrero;
use App\Models\RazasGanado;
use Illuminate\Support\Facades\Auth;
/* use App\Helpers\IntelephenseHelpers; */



class InventarioController extends Controller
{
    //
    public function index()
    {
        // Obtener el usuario autenticado
        
        $user = Auth::user();
        $animales = Animal::filtrarPorEstadoYPredio($user )->get();
        if ($user->role->name === 'admin') {
            $predios = Predios::all(); // Todos los predios
        } else {
            $predios = $user->predios;
        }
        $predios = $user->predios;
        $lotes = Lote::whereIn('predio_id', $predios->pluck('id'))->get();
        $potreros = Potrero::whereIn('predio_id', $predios->pluck('id'))->get();
        // Obtener razas
        if ($user->role->name === 'admin') {
            // Administrador ve todas las razas
            $razas = RazasGanado::all();
        } elseif ($user->role->name === 'propietario') {
            // Propietario ve las razas creadas por él, por administradores y las que no tienen creador
            $razas = RazasGanado::where('created_by', $user->id)
                ->orWhereIn('created_by', function ($query) {
                    $query->select('id')
                          ->from('users')
                          ->where('id_rol', 1); // Usuarios con rol de administrador
                })
                ->orWhereNull('created_by') // Razas sin creador específico
                ->get();
        } else {
            // Si no es administrador ni propietario, denegar el acceso
            abort(403, 'No tienes permiso para acceder a esta información.');
        }

        // Obtener los partos de los animales en los predios del usuario
        $partos = PartoAnimal::whereIn('id_animal', $animales->pluck('id_animal'))->get();

        // Estados reproductivos y productivos (puedes llenarlos según tu lógica)
        $estadosReproductivos = [];
        $estadosProductivos = [];

        // Retornar la vista con los datos
        return view('inventario_animales.index', compact(
            'animales',
            'partos',
            'estadosReproductivos',
            'estadosProductivos',
            'predios',
            'lotes',
            'razas',
            'potreros'
        ));
    }


    public function getAnimals()
{
    $user = Auth::user();
    $animales = Animal::filtrarPorEstadoYPredio($user)->get();
    // Si es necesario, formatea o transforma los datos antes de enviarlos.
    return response()->json(['animals' => $animales]);
}


}
