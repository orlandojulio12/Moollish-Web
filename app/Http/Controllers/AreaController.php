<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AreaRequest;
use App\Models\Areas;
use App\Models\Predios;
use App\Models\TiposAreas;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
    
        if ($user->role->name === 'admin') {
            // Administrador ve todas las áreas
            $areas = Areas::all();
        } elseif ($user->role->name === 'propietario') {
            // Propietario ve las áreas creadas por él, por administradores y las que no tienen creador
            $areas = Areas::where('created_by', $user->id)
                ->orWhereIn('created_by', function ($query) {
                    $query->select('id')
                          ->from('users')
                          ->where('id_rol', 1); // Usuarios con rol de administrador
                })
                ->orWhereNull('created_by')
                ->get();
        } else {
            // Si no es administrador ni propietario, denegar el acceso
            abort(403, 'No tienes permiso para acceder a esta información.');
        }
    
        return view('area.index', compact('areas'));
    }
    
    public function store(AreaRequest $request): RedirectResponse
    {
        try {
            // Validar y preparar los datos
            $data = $request->validated();
            $data['created_by'] = auth()->user()->id; // Asignar el usuario autenticado como creador
    
            // Crear el área
            Areas::create($data);
    
            // Enviar mensaje de éxito al usuario
            return Redirect::route('areas.index')
                ->with('success', 'Área creada exitosamente.');
        } catch (\Exception $e) {
            // Registrar el error en el log
            Log::error('Error al crear el área: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'data' => $request->all(),
            ]);
    
            // Enviar mensaje de error al usuario
            return Redirect::route('areas.index')
                ->with('error', 'Hubo un problema al crear el área. Intenta nuevamente más tarde.');
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $area = new Areas();

        return view('area.create', compact('area'));
    }

    /**
     * Store a newly created resource in storage.
     */
   

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $area = Areas::find($id);

        return view('area.show', compact('area'));
    }

    public function hectareas()
    {
        // Obtener áreas en Hectáreas agrupadas por `id_predio`
        $areas = Areas::where('tipo_medidas', 'Hectáreas')
            ->select('id_predio', DB::raw('SUM(medidas) as total_hectareas'))
            ->groupBy('id_predio')
            ->get();

        return view('area.hectareas', compact('areas'));
    }

    public function metrosCuadrados()
    {
        // Obtener áreas en Metros Cuadrados agrupadas por `id_predio`
        $areas = Areas::where('tipo_medidas', 'Metros Cuadrados')
            ->select('id_predio', DB::raw('SUM(medidas) as total_metros'))
            ->groupBy('id_predio')
            ->get();

        return view('area.metros_cuadrados', compact('areas'));
    }

    public function detalleHectareas($id)
    {
        $areas = Areas::where('id_predio', $id)
                      ->where('tipo_medidas', 'Hectáreas')
                      ->with('TiposAreas') // Mantener el nombre TiposAreas
                      ->select('id', 'medidas', 'id_tipo_area')
                      ->get();

        $predio = Predios::findOrFail($id)->nombre_predio;

        return view('area.detalle_hectareas', compact('areas', 'predio'));
    }


    public function detalleMetrosCuadrados($id)
    {
        $areas = Areas::where('id_predio', $id)
                      ->where('tipo_medidas', 'Metros Cuadrados')
                      ->with('TiposAreas') // Cargar la relación con TiposAreas
                      ->select('id', 'medidas', 'id_tipo_area')
                      ->get();

        $predio = Predios::findOrFail($id)->nombre_predio;

        return view('area.detalle_metros_cuadrados', compact('areas', 'predio'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $area = TiposAreas::find($id);

        return view('area.edit', compact('area'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AreaRequest $request, Areas $area): RedirectResponse
    {
        $area->update($request->validated());

        return Redirect::route('areas.index')
            ->with('success', 'Area updated successfully');
    }

    public function destroy($id): RedirectResponse
{
    $area = Areas::findOrFail($id);
    $currentUser = auth()->user();

    // Verificar permisos
    if ($currentUser->role->name !== 'admin' && $area->created_by !== $currentUser->id) {
        abort(403, 'No tienes permiso para eliminar esta área.');
    }

    $area->delete();

    return Redirect::route('areas.index')
        ->with('success', 'Área eliminada exitosamente.');
}

}
