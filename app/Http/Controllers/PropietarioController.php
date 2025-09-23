<?php

namespace App\Http\Controllers;

use App\Models\Propietario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PropietarioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PropietarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
{
    // Verificar si el usuario autenticado tiene el rol de 'admin'
    if (Auth::check() && Auth::user()->role->name == 'admin') {
        // Si es admin, mostrar todos los registros
        $propietarios = Propietario::paginate();
    } else {
        // Si es propietario, mostrar solo los registros creados por él
        $propietarios = Propietario::where('id_user', Auth::id())->paginate();
    }

    // Devolver la vista con los propietarios filtrados
    return view('propietario.index', compact('propietarios'))
        ->with('i', ($request->input('page', 1) - 1) * $propietarios->perPage());
}


    /**
     * Show the form for creating a new resource.
     */

public function create(): View
{
    $propietario = new Propietario();

    // Obtener todos los usuarios con el rol de 'propietario'
    $propietarios = User::whereHas('role', function ($query) {
        $query->where('name', 'propietario');
    })->get();

    // Pasar los propietarios a la vista
    return view('propietario.create', compact('propietario', 'propietarios'));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(PropietarioRequest $request): RedirectResponse
    {
        Propietario::create($request->validated());

        return Redirect::route('propietarios.index')
            ->with('success', 'Propietario created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $propietario = Propietario::find($id);

        return view('propietario.show', compact('propietario'));
    }

    public function mostrarGraficaPrediosPorPropietario()
{
    // Realiza la consulta para contar predios por propietario
    $prediosPorPropietario = DB::table('propietarios')
        ->join('predios', 'propietarios.id', '=', 'predios.id_propietario')
        ->select('propietarios.nombre_completo', DB::raw('COUNT(predios.id) as total_predios'))
        ->groupBy('propietarios.nombre_completo')
        ->get();

    // Prepara los datos para la gráfica
    $propietarios = $prediosPorPropietario->pluck('nombre_completo')->toArray();
    $totalPredios = $prediosPorPropietario->pluck('total_predios')->toArray();

    // Retorna los datos a la vista
    return view('propietario.grafica', compact('propietarios', 'totalPredios'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $propietario = Propietario::find($id); // El propietario que estás editando
        $propietarios = User::whereHas('role', function ($query) {
            $query->where('name', 'propietario');
        })->get(); // Todos los propietarios para el select

        return view('propietario.edit', compact('propietario', 'propietarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PropietarioRequest $request, Propietario $propietario): RedirectResponse
    {
        $propietario->update($request->validated());

        return Redirect::route('propietarios.index')
            ->with('success', 'Propietario updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Propietario::find($id)->delete();

        return Redirect::route('propietarios.index')
            ->with('success', 'Propietario deleted successfully');
    }
}
