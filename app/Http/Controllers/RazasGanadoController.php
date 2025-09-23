<?php

namespace App\Http\Controllers;

use App\Models\RazasGanado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RazasGanadoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class RazasGanadoController extends Controller
{
    public function index(Request $request): View
{
    // Obtener el usuario autenticado
    $user = auth()->user();
    
    // Consultar las razas dependiendo del rol del usuario
    if ($user->role->name === 'admin') {
        // Administrador ve todas las razas
        $razasGanados = RazasGanado::all();
    } elseif ($user->role->name === 'propietario') {
        // Propietario ve las razas creadas por él, por administradores y las que no tienen creador
        $razasGanados = RazasGanado::where('created_by', $user->id)
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

    return view('razas-ganado.index', compact('razasGanados'));
}
        
    public function store(RazasGanadoRequest $request): RedirectResponse
    {
        try {
            // Validar y preparar los datos
            $data = $request->validated();
            $data['created_by'] = auth()->user()->id; // Asignar el usuario autenticado como creador
        
            // Intentar crear la raza
            RazasGanado::create($data);
    
            // Enviar mensaje de éxito al usuario
            return Redirect::route('razas-ganados.index')
                ->with('success', 'Raza de ganado creada exitosamente.');
        } catch (\Exception $e) {
            // Registrar el error en el log
            Log::error('Error al crear la raza de ganado: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'data' => $request->all(),
            ]);
    
            // Enviar mensaje de error al usuario
            return Redirect::route('razas-ganados.index')
                ->with('error', 'Hubo un problema al crear la raza de ganado. Intenta nuevamente más tarde.');
        }
    }
    
    public function create(): View
    {
        $razasGanado = new RazasGanado();

        return view('razas-ganado.create', compact('razasGanado'));
    }

    public function show($id): View
    {
        $razasGanado = RazasGanado::find($id);

        return view('razas-ganado.show', compact('razasGanado'));
    }
    public function edit($id): View
    {
        $razasGanado = RazasGanado::find($id);

        return view('razas-ganado.edit', compact('razasGanado'));
    }

    public function update(RazasGanadoRequest $request, RazasGanado $razasGanado): RedirectResponse
    {
        $razasGanado->update($request->validated());

        return Redirect::route('razas-ganados.index')
            ->with('success', 'RazasGanado updated successfully');
    }

    public function destroy($id): RedirectResponse
{
    $razasGanado = RazasGanado::findOrFail($id);
    $currentUser = auth()->user();

    // Verificar permisos
    if ($currentUser->role->name !== 'admin' && $razasGanado->created_by !== $currentUser->id) {
        abort(403, 'No tienes permiso para eliminar esta raza.');
    }

    $razasGanado->delete();

    return Redirect::route('razas-ganados.index')
        ->with('success', 'Raza eliminada exitosamente.');
}

}
