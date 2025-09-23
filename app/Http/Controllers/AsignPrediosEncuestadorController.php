<?php

namespace App\Http\Controllers;

use App\Models\AsignPrediosEncuestador;
use App\Models\Predios;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsignPrediosEncuestadorController extends Controller
{
    /**
     * Mostrar una asignación específica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
    
        // Obtener las asignaciones donde el predio pertenece a un propietario cuyo id_user coincide con el usuario autenticado
        $asignaciones = AsignPrediosEncuestador::whereHas('predio', function ($query) use ($user) {
            $query->whereHas('propietario', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            });
        })->get();
    
        // Obtener todos los predios que no tienen asignaciones para la store
        $predios = Predios::whereDoesntHave('asignaciones')->get();
    
        // Inicializar $prediosUpdate como un array vacío por defecto
        $prediosUpdate = [];
    
        // Verificar si hay una asignación actual para la edición
        $asignacion = AsignPrediosEncuestador::where('id_encuestador', $user->id)->first();
    
        // Si hay asignación, incluir el predio asignado
        if ($asignacion) {
            $prediosUpdate = Predios::whereDoesntHave('asignaciones', function ($query) use ($asignacion) {
                $query->where('id', '!=', $asignacion->id_predio);
            })->orWhere('id', $asignacion->id_predio)->get();
        }
    
        // Obtener todos los usuarios con el rol de encuestador y estado activo
        $encuestadores = User::whereHas('role', function ($query) {
            $query->where('name', 'encuestador'); // Asumiendo que el nombre del rol es 'encuestador'
        })->where('estado', 'Activo')->get();
    
        // Retornar la vista con los datos necesarios
        return view('asignaciones.show', compact('asignaciones', 'predios', 'prediosUpdate', 'encuestadores', 'asignacion'));
    }
    
    

    /**
     * Guardar una nueva asignación.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'id_encuestador' => 'required|exists:users,id',
                'id_predio' => 'required|exists:predios,id', 
            ]);
    
            // Crear una nueva asignación
            AsignPrediosEncuestador::create($validatedData);
    
            // Redirigir con mensaje de éxito
            return redirect()->back()->with('success', 'Asignación creada con éxito');
        } catch (\Exception $e) {
            // Redirigir de vuelta con mensaje de error
            return redirect()->back()->with('error', 'Ocurrió un error al crear la asignación: ' . $e->getMessage());
        }
    }    

    /**
     * Actualizar una asignación existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'id_encuestador' => 'required|exists:users,id',
                'id_predio' => 'required|exists:predios,id',
            ]);
    
            // Encontrar la asignación y actualizarla
            $asignacion = AsignPrediosEncuestador::findOrFail($id);
            $asignacion->update($validatedData);
    
            // Redirigir con mensaje de éxito
            return redirect()->back()->with('success', 'Asignación actualizada con éxito');
        } catch (\Exception $e) {
            // Redirigir de vuelta con mensaje de error
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar la asignación: ' . $e->getMessage());
        }
    }

    public function destroy($id)
{
    try {
        // Buscar la asignación por id y eliminarla
        $asignacion = AsignPrediosEncuestador::findOrFail($id);
        $asignacion->delete();

        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Asignación eliminada con éxito');
    } catch (\Exception $e) {
        // Redirigir de vuelta con un mensaje de error
        return redirect()->back()->with('error', 'Ocurrió un error al eliminar la asignación: ' . $e->getMessage());
    }
}

    
}
