<?php

namespace App\Http\Controllers;

use App\Models\InstalacionesEquipos;
use App\Models\TiposInstalacionesEquipo;
use App\Models\Predios;
use Illuminate\Http\Request;

class InstalacionesEquiposController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $tipos_equipos = TiposInstalacionesEquipo::all();
        $predioId = Predios::findOrFail($id)->id;
        $ManExists = InstalacionesEquipos::where('id_predio', $predioId)->exists();
        $instalacionesEquipos = InstalacionesEquipos::where('id_predio', $predioId)->get()->keyBy('id_tipos_equipos');
        return view('instalaciones_equipos.create', compact('predioId', 'tipos_equipos', 'ManExists', 'instalacionesEquipos'));
    }

    public function store(Request $request)
{
    try {
        // Validar los datos del formulario
        $request->validate([
            'id_predio' => 'required|exists:predios,id',
            'equipos' => 'required|array',
            'equipos.*.si' => 'nullable|string',
            'equipos.*.no' => 'nullable|string',
            'equipos.*.especificar' => 'nullable|string',
            'equipos.*.id_tipos_equipos' => 'required|exists:tipos_instalaciones_equipos,id',
        ]);

        // Crear cada registro de equipo
        foreach ($request->equipos as $equipo) {
            InstalacionesEquipos::create([
                'id_predio' => $request->id_predio,
                'id_tipos_equipos' => $equipo['id_tipos_equipos'],
                'si' => $equipo['si'] ?? false,
                'no' => $equipo['no'] ?? false,
                'especificar' => $equipo['especificar'] ?? '',
            ]);
        }

        // Redirigir a la vista Seccion2 después de guardar
        return redirect()->route('Seccion2', ['id' => $request->id_predio])
                         ->with('success', 'Instalaciones y equipos guardados correctamente');
    } catch (\Exception $e) {
        // Capturar cualquier error y mostrar un mensaje
        return back()->with('error', 'Ocurrió un error al guardar los equipos: ' . $e->getMessage());
    }
}


public function update(Request $request, $id)
{
    try {
        // Validar los datos del formulario
        $request->validate([
            'id_predio' => 'required|exists:predios,id',
            'equipos' => 'required|array',
            'equipos.*.si' => 'nullable|string',
            'equipos.*.no' => 'nullable|string',
            'equipos.*.especificar' => 'nullable|string',
            'equipos.*.id_tipos_equipos' => 'required|exists:tipos_instalaciones_equipos,id',
        ]);

        // Actualizar cada registro relacionado con los equipos
        foreach ($request->equipos as $equipoId => $equipoData) {
            $instalacionEquipo = InstalacionesEquipos::where('id_predio', $request->id_predio)
                                ->where('id_tipos_equipos', $equipoData['id_tipos_equipos'])
                                ->firstOrFail();

            // Actualizar los datos del equipo
            $instalacionEquipo->update([
                'si' => $equipoData['si'] ?? null,
                'no' => $equipoData['no'] ?? null,
                'especificar' => $equipoData['especificar'] ?? null,
            ]);
        }

        // Redirigir a la vista Seccion2 después de actualizar
        return redirect()->route('Seccion2', ['id' => $request->id_predio])
                         ->with('success', 'Información de los equipos actualizada correctamente');
    } catch (\Exception $e) {
        // Capturar cualquier error y mostrar un mensaje
        return back()->with('error', 'Ocurrió un error al actualizar los equipos: ' . $e->getMessage());
    }
}

    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstalacionesEquipos $instalacionesEquipos)
    {
        //
    }
}
