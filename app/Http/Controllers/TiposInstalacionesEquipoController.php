<?php

namespace App\Http\Controllers;

use App\Models\TiposInstalacionesEquipo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TiposInstalacionesEquipoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TiposInstalacionesEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tiposInstalacionesEquipos = TiposInstalacionesEquipo::paginate();

        return view('tipos-instalaciones-equipo.index', compact('tiposInstalacionesEquipos'))
            ->with('i', ($request->input('page', 1) - 1) * $tiposInstalacionesEquipos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tiposInstalacionesEquipo = new TiposInstalacionesEquipo();

        return view('tipos-instalaciones-equipo.create', compact('tiposInstalacionesEquipo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TiposInstalacionesEquipoRequest $request): RedirectResponse
    {
        TiposInstalacionesEquipo::create($request->validated());

        return Redirect::route('tipos-instalaciones-equipos.index')
            ->with('success', 'TiposInstalacionesEquipo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $tiposInstalacionesEquipo = TiposInstalacionesEquipo::find($id);

        return view('tipos-instalaciones-equipo.show', compact('tiposInstalacionesEquipo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tiposInstalacionesEquipo = TiposInstalacionesEquipo::find($id);

        return view('tipos-instalaciones-equipo.edit', compact('tiposInstalacionesEquipo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TiposInstalacionesEquipoRequest $request, TiposInstalacionesEquipo $tiposInstalacionesEquipo): RedirectResponse
    {
        $tiposInstalacionesEquipo->update($request->validated());

        return Redirect::route('tipos-instalaciones-equipos.index')
            ->with('success', 'TiposInstalacionesEquipo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        TiposInstalacionesEquipo::find($id)->delete();

        return Redirect::route('tipos-instalaciones-equipos.index')
            ->with('success', 'TiposInstalacionesEquipo deleted successfully');
    }
}
